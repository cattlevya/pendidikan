import Foundation

struct PopularMoviesResponse: Codable {
	let page: Int
	let results: [Movie]
}

struct Movie: Codable, Identifiable, Hashable {
	let id: Int
	let title: String
	let original_title: String?
	let overview: String?
	let poster_path: String?
	let backdrop_path: String?
	let release_date: String?
	let popularity: Double?
	let vote_average: Double?
	let vote_count: Int?
}

extension MovieEntity {
	func update(from movie: Movie) {
		self.id = Int64(movie.id)
		self.title = movie.title
		self.originalTitle = movie.original_title
		self.overview = movie.overview
		self.posterPath = movie.poster_path
		self.backdropPath = movie.backdrop_path
		self.releaseDate = movie.release_date
		self.popularity = movie.popularity ?? 0
		self.voteAverage = movie.vote_average ?? 0
		self.voteCount = Int64(movie.vote_count ?? 0)
	}
	
	var asDomain: Movie {
		Movie(
			id: Int(id),
			title: title,
			original_title: originalTitle,
			overview: overview,
			poster_path: posterPath,
			backdrop_path: backdropPath,
			release_date: releaseDate,
			popularity: popularity,
			vote_average: voteAverage,
			vote_count: Int(voteCount)
		)
	}
}

extension Movie {
	var posterURL: URL? {
		guard let path = poster_path else { return nil }
		return URL(string: "https://image.tmdb.org/t/p/w500/\(path)")
	}
	var backdropURL: URL? {
		guard let path = backdrop_path else { return nil }
		return URL(string: "https://image.tmdb.org/t/p/w500/\(path)")
	}
	var videoURL: URL? {
		URL(string: "https://vidsrc.icu/embed/movie/\(id)")
	}
}