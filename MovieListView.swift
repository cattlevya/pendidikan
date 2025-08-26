import SwiftUI
import CoreData

struct MovieListView: View {
	@Environment(\.managedObjectContext) private var context
	@StateObject private var viewModelHolder = ViewModelHolder()

	var body: some View {
		NavigationView {
			Group {
				if viewModelHolder.vm.isLoading && viewModelHolder.vm.movies.isEmpty {
					ProgressView()
				} else {
					List(viewModelHolder.vm.movies) { movie in
						NavigationLink(destination: MovieDetailView(movie: movie)) {
							HStack(alignment: .top, spacing: 12) {
								RemoteImage(url: movie.posterURL) { Color.gray.opacity(0.2) }
									.frame(width: 70, height: 100)
									.clipped()
								VStack(alignment: .leading, spacing: 6) {
									Text(movie.title).font(.headline)
									Text(movie.release_date).font(.subheadline).foregroundColor(.secondary)
								}
								Spacer()
							}
						}
					}
				}
			}
			.navigationTitle("Popular Movies")
			.toolbar { ToolbarItem(placement: .navigationBarTrailing) { Button(action: { Task { await viewModelHolder.vm.refresh() } }) { Image(systemName: "arrow.clockwise") } } }
		}
		.searchable(text: $viewModelHolder.vm.searchText, placement: .navigationBarDrawer(displayMode: .always), prompt: "Search title")
		.onAppear { viewModelHolder.configureIfNeeded(context: context) }
	}

	private final class ViewModelHolder: ObservableObject {
		@Published var vm: MovieListViewModel!
		func configureIfNeeded(context: NSManagedObjectContext) {
			if vm == nil {
				vm = MovieListViewModel(context: context)
				vm.onAppear()
			}
		}
	}
}