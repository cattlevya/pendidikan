import Foundation
import Combine
import CoreData

@MainActor
final class MovieListViewModel: ObservableObject {
	@Published var movies: [Movie] = []
	@Published var searchText: String = ""
	@Published var isLoading: Bool = false
	@Published var errorMessage: String?

	private let repository: MovieRepositoryProtocol
	private var cancellables: Set<AnyCancellable> = []

	init(context: NSManagedObjectContext) {
		self.repository = MovieRepository(context: context)
		bindSearch()
	}

	func onAppear() {
		Task { await refresh() }
		loadCached()
	}

	func refresh() async {
		isLoading = true
		defer { isLoading = false }
		await repository.refreshPopular()
		loadCached()
	}

	private func loadCached() {
		do {
			movies = try repository.fetchCachedPopular(matching: searchText.isEmpty ? nil : searchText)
		} catch {
			errorMessage = error.localizedDescription
		}
	}

	private func bindSearch() {
		$searchText
			.removeDuplicates()
			.debounce(for: .milliseconds(300), scheduler: DispatchQueue.main)
			.sink { [weak self] _ in
				self?.loadCached()
			}
			.store(in: &cancellables)
	}
}