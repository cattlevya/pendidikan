import Foundation

protocol TMDBClientProtocol {
	func fetchPopularMovies(page: Int) async throws -> [Movie]
}

final class TMDBClient: TMDBClientProtocol {
	private let baseURL = URL(string: "https://api.themoviedb.org/3")!
	private let apiKey = "25306eedec9389c60b0d605dcd541415"
	private let urlSession: URLSession
	
	init(urlSession: URLSession = .shared) {
		self.urlSession = urlSession
	}
	
	func fetchPopularMovies(page: Int) async throws -> [Movie] {
		var components = URLComponents(url: baseURL.appendingPathComponent("movie/popular"), resolvingAgainstBaseURL: false)!
		components.queryItems = [
			URLQueryItem(name: "api_key", value: apiKey),
			URLQueryItem(name: "page", value: String(page))
		]
		let (data, response) = try await urlSession.data(from: components.url!)
		guard let http = response as? HTTPURLResponse, (200..<300).contains(http.statusCode) else {
			throw URLError(.badServerResponse)
		}
		let decoder = JSONDecoder()
		decoder.keyDecodingStrategy = .useDefaultKeys
		let res = try decoder.decode(PopularMoviesResponse.self, from: data)
		return res.results
	}
}