import Foundation
import CoreData

protocol MovieRepositoryProtocol {
	func refreshPopular() async
	func fetchCachedPopular(matching query: String?) throws -> [Movie]
}

final class MovieRepository: MovieRepositoryProtocol {
	private let client: TMDBClientProtocol
	private let context: NSManagedObjectContext

	init(client: TMDBClientProtocol = TMDBClient.shared, context: NSManagedObjectContext) {
		self.client = client
		self.context = context
	}

	func refreshPopular() async {
		do {
			let movies = try await client.fetchPopularMovies(page: 1)
			try persist(movies: movies)
		} catch {
			print("Failed to refresh: \(error)")
		}
	}

	func fetchCachedPopular(matching query: String?) throws -> [Movie] {
		let request = NSFetchRequest<MovieEntity>(entityName: "MovieEntity")
		var predicates: [NSPredicate] = []
		if let q = query, !q.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
			predicates.append(NSPredicate(format: "title CONTAINS[cd] %@", q))
		}
		request.predicate = predicates.isEmpty ? nil : NSCompoundPredicate(andPredicateWithSubpredicates: predicates)
		request.sortDescriptors = [NSSortDescriptor(key: "popularity", ascending: false)]
		let entities = try context.fetch(request)
		return entities.map { e in
			Movie(
				id: Int(e.id),
				title: e.title ?? "",
				overview: e.overview ?? "",
				release_date: e.releaseDate ?? "",
				poster_path: e.posterPath,
				backdrop_path: e.backdropPath,
				popularity: e.popularity,
				vote_average: e.voteAverage,
				vote_count: Int(e.voteCount),
				video: nil
			)
		}
	}

	private func persist(movies: [Movie]) throws {
		let request = NSFetchRequest<MovieEntity>(entityName: "MovieEntity")
		let existing = try context.fetch(request)
		var byId: [Int64: MovieEntity] = Dictionary(uniqueKeysWithValues: existing.map { ($0.id, $0) })

		for m in movies {
			let key = Int64(m.id)
			let entity = byId[key] ?? MovieEntity(context: context)
			entity.id = key
			entity.title = m.title
			entity.overview = m.overview
			entity.releaseDate = m.release_date
			entity.posterPath = m.poster_path
			entity.backdropPath = m.backdrop_path
			entity.popularity = m.popularity
			entity.voteAverage = m.vote_average
			entity.voteCount = Int64(m.vote_count)
			byId[key] = entity
		}

		if context.hasChanges {
			try context.save()
		}
	}
}