import Combine
import Foundation

@MainActor
final class MovieListViewModel: ObservableObject {
	@Published var searchText: String = ""
	@Published private(set) var displayed: [Movie] = []
	@Published private(set) var isLoading: Bool = false
	@Published var errorMessage: String?
	
	private let repository: MovieRepository
	private var cancellables: Set<AnyCancellable> = []
	
	init(repository: MovieRepository) {
		self.repository = repository
		repository.$movies
			.combineLatest($searchText.removeDuplicates())
			.map { movies, query in
				guard !query.isEmpty else { return movies }
				return movies.filter { $0.title.localizedCaseInsensitiveContains(query) }
			}
			.assign(to: &$displayed)
		repository.$errorMessage.assign(to: &$errorMessage)
	}
	
	func refresh() async {
		isLoading = true
		await repository.refreshPopular()
		isLoading = false
	}
}