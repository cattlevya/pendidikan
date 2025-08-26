import Foundation

protocol TMDBClientProtocol {
	func fetchPopularMovies(page: Int) async throws -> [Movie]
}

final class TMDBClient: TMDBClientProtocol {
	static let shared = TMDBClient()
	private init() {}

	private let session: URLSession = {
		let config = URLSessionConfiguration.default
		config.requestCachePolicy = .reloadIgnoringLocalCacheData
		config.urlCache = URLCache(memoryCapacity: 50 * 1024 * 1024, diskCapacity: 200 * 1024 * 1024)
		return URLSession(configuration: config)
	}()

	private let apiKey = "25306eedec9389c60b0d605dcd541415"

	func fetchPopularMovies(page: Int = 1) async throws -> [Movie] {
		guard let url = URL(string: "https://api.themoviedb.org/3/movie/popular?api_key=\(apiKey)&page=\(page)") else {
			throw URLError(.badURL)
		}
		let (data, response) = try await session.data(from: url)
		guard let http = response as? HTTPURLResponse, (200..<300).contains(http.statusCode) else {
			throw URLError(.badServerResponse)
		}
		let decoder = JSONDecoder()
		let res = try decoder.decode(MovieResponse.self, from: data)
		return res.results
	}
}