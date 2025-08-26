import SwiftUI
import WebKit

struct MovieDetailView: View {
	let movie: Movie

	var body: some View {
		ScrollView {
			VStack(alignment: .leading, spacing: 16) {
				RemoteImage(url: movie.backdropURL ?? movie.posterURL) { Color.gray.opacity(0.2) }
					.frame(height: 220)
					.clipped()

				VStack(alignment: .leading, spacing: 8) {
					Text(movie.title).font(.title2).bold()
					Text("Release: \(movie.release_date)").foregroundColor(.secondary)
				}

				Text(movie.overview).font(.body)

				HStack(spacing: 16) {
					Label("\(String(format: "%.1f", movie.vote_average))", systemImage: "star.fill").foregroundColor(.yellow)
					Label("\(movie.vote_count)", systemImage: "person.3")
					Label("\(Int(movie.popularity))", systemImage: "flame")
				}

				Text("Trailer").font(.headline)
				VideoWebView(url: movie.videoURL)
					.frame(height: 240)
			}
			.padding()
		}
		.navigationTitle("Detail")
		.navigationBarTitleDisplayMode(.inline)
	}
}

struct VideoWebView: UIViewRepresentable {
	let url: URL
	func makeUIView(context: Context) -> WKWebView { WKWebView() }
	func updateUIView(_ uiView: WKWebView, context: Context) {
		uiView.scrollView.isScrollEnabled = false
		uiView.load(URLRequest(url: url))
	}
}