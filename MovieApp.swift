import SwiftUI

@main
struct MovieApp: App {
	@StateObject private var coreDataStack = CoreDataStack()
	@StateObject private var repository: MovieRepository
	
	init() {
		let stack = CoreDataStack()
		_coreDataStack = StateObject(wrappedValue: stack)
		_repository = StateObject(wrappedValue: MovieRepository(coreDataStack: stack))
	}
	
	var body: some Scene {
		WindowGroup {
			MovieListView(repository: repository)
		}
	}
}