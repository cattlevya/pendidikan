import Foundation

struct MovieResponse: Decodable {
	let page: Int
	let results: [Movie]
}

struct Movie: Identifiable, Decodable, Equatable {
	let id: Int
	let title: String
	let overview: String
	let release_date: String
	let poster_path: String?
	let backdrop_path: String?
	let popularity: Double
	let vote_average: Double
	let vote_count: Int
	let video: Bool?

	var posterURL: URL? { URL(string: "https://image.tmdb.org/t/p/w500\(poster_path ?? "")") }
	var backdropURL: URL? { URL(string: "https://image.tmdb.org/t/p/w500\(backdrop_path ?? "")") }
	var videoURL: URL { URL(string: "https://vidsrc.icu/embed/movie/\(id)")! }
}