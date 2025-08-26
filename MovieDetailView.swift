import SwiftUI

struct MovieDetailView: View {
	let movie: Movie
	
	var body: some View {
		ScrollView {
			VStack(alignment: .leading, spacing: 16) {
				if let backdrop = movie.backdropURL {
					URLImageView(url: backdrop, placeholder: "No Image")
						.frame(height: 200)
						.clipped()
				}
				HStack(alignment: .top, spacing: 12) {
					URLImageView(url: movie.posterURL, placeholder: "No Image")
						.frame(width: 100, height: 150)
						.clipped()
					VStack(alignment: .leading, spacing: 8) {
						Text(movie.title).font(.title2).bold()
						Text("Release: \(movie.release_date ?? "-")")
						Text("Rating: \(String(format: "%.1f", movie.vote_average ?? 0)) (\(movie.vote_count ?? 0))")
						Text("Popularity: \(Int(movie.popularity ?? 0))")
					}
				}
				if let overview = movie.overview, !overview.isEmpty {
					Text("Overview").font(.headline)
					Text(overview)
				}
				if let url = movie.videoURL {
					Text("Trailer / Video").font(.headline)
					WebView(url: url)
						.frame(height: 240)
						.cornerRadius(8)
				}
			}
			.padding()
		}
		.navigationTitle(movie.title)
		.navigationBarTitleDisplayMode(.inline)
	}
}