import SwiftUI

struct MovieListView: View {
	@StateObject private var viewModel: MovieListViewModel
	
	init(repository: MovieRepository) {
		_stateObject = StateObject(wrappedValue: MovieListViewModel(repository: repository))
	}
	
	var body: some View {
		NavigationStack {
			listContent
				.navigationTitle("Popular Movies")
		}
		.searchable(text: $viewModel.searchText, placement: .navigationBarDrawer(displayMode: .always))
		.task { await viewModel.refresh() }
	}
	
	@ViewBuilder
	private var listContent: some View {
		List(viewModel.displayed, id: \.self) { movie in
			NavigationLink(value: movie) {
				HStack(alignment: .top, spacing: 12) {
					URLImageView(url: movie.posterURL, placeholder: "No Image")
						.frame(width: 60, height: 90)
						.clipped()
					VStack(alignment: .leading, spacing: 6) {
						Text(movie.title).font(.headline)
						Text(movie.release_date ?? "-").font(.subheadline).foregroundColor(.secondary)
					}
					Spacer()
				}
			}
		}
		.refreshable { await viewModel.refresh() }
		.navigationDestination(for: Movie.self) { movie in
			MovieDetailView(movie: movie)
		}
	}
}