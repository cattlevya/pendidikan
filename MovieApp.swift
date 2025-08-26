import SwiftUI

@main
struct MovieApp: App {
	let persistenceController = PersistenceController.shared

	var body: some Scene {
		WindowGroup {
			MovieListView()
				.environment(\.managedObjectContext, persistenceController.container.viewContext)
		}
	}
}