import Combine
import CoreData
import Foundation

final class MovieRepository: ObservableObject {
	private let coreDataStack: CoreDataStack
	private let client: TMDBClientProtocol
	
	@Published private(set) var movies: [Movie] = []
	@Published private(set) var errorMessage: String?
	
	private var cancellables: Set<AnyCancellable> = []
	
	init(coreDataStack: CoreDataStack, client: TMDBClientProtocol = TMDBClient()) {
		self.coreDataStack = coreDataStack
		self.client = client
		loadFromStore()
		Task { await refreshPopular() }
	}
	
	func refreshPopular() async {
		do {
			let fetched = try await client.fetchPopularMovies(page: 1)
			saveToStore(movies: fetched)
			DispatchQueue.main.async { self.movies = fetched }
		} catch {
			DispatchQueue.main.async { self.errorMessage = error.localizedDescription }
		}
	}
	
	private func loadFromStore() {
		let context = coreDataStack.persistentContainer.viewContext
		let request = NSFetchRequest<MovieEntity>(entityName: "MovieEntity")
		request.sortDescriptors = [NSSortDescriptor(key: "popularity", ascending: false)]
		do {
			let entities = try context.fetch(request)
			self.movies = entities.map { $0.asDomain }
		} catch {
			self.errorMessage = error.localizedDescription
		}
	}
	
	private func saveToStore(movies: [Movie]) {
		let context = coreDataStack.newBackgroundContext()
		context.perform {
			let fetch = NSFetchRequest<MovieEntity>(entityName: "MovieEntity")
			let existing: [MovieEntity]
			do { existing = try context.fetch(fetch) } catch { existing = [] }
			let existingById = Dictionary(uniqueKeysWithValues: existing.map { (Int($0.id), $0) })
			for movie in movies {
				let entity = existingById[movie.id] ?? MovieEntity(context: context)
				entity.update(from: movie)
			}
			do { try context.save() } catch { print("Failed save: \(error)") }
		}
	}
}