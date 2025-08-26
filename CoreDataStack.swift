import CoreData
import Foundation

final class CoreDataStack: ObservableObject {
	let persistentContainer: NSPersistentContainer
	
	init(inMemory: Bool = false) {
		let model = CoreDataStack.makeModel()
		persistentContainer = NSPersistentContainer(name: "MovieModel", managedObjectModel: model)
		if inMemory {
			let description = NSPersistentStoreDescription()
			description.type = NSInMemoryStoreType
			persistentContainer.persistentStoreDescriptions = [description]
		}
		persistentContainer.loadPersistentStores { _, error in
			if let error = error {
				fatalError("Unresolved error: \(error)")
			}
		}
		persistentContainer.viewContext.mergePolicy = NSMergeByPropertyObjectTrumpMergePolicy
		persistentContainer.viewContext.automaticallyMergesChangesFromParent = true
	}
	
	static func makeModel() -> NSManagedObjectModel {
		let model = NSManagedObjectModel()
		let entity = NSEntityDescription()
		entity.name = "MovieEntity"
		entity.managedObjectClassName = NSStringFromClass(MovieEntity.self)
		
		var properties: [NSAttributeDescription] = []
		
		let idAttr = NSAttributeDescription()
		idAttr.name = "id"
		idAttr.attributeType = .integer64AttributeType
		idAttr.isOptional = false
		properties.append(idAttr)
		
		let titleAttr = NSAttributeDescription()
		titleAttr.name = "title"
		titleAttr.attributeType = .stringAttributeType
		titleAttr.isOptional = false
		properties.append(titleAttr)
		
		let originalTitleAttr = NSAttributeDescription()
		originalTitleAttr.name = "originalTitle"
		originalTitleAttr.attributeType = .stringAttributeType
		originalTitleAttr.isOptional = true
		properties.append(originalTitleAttr)
		
		let overviewAttr = NSAttributeDescription()
		overviewAttr.name = "overview"
		overviewAttr.attributeType = .stringAttributeType
		overviewAttr.isOptional = true
		properties.append(overviewAttr)
		
		let posterPathAttr = NSAttributeDescription()
		posterPathAttr.name = "posterPath"
		posterPathAttr.attributeType = .stringAttributeType
		posterPathAttr.isOptional = true
		properties.append(posterPathAttr)
		
		let backdropPathAttr = NSAttributeDescription()
		backdropPathAttr.name = "backdropPath"
		backdropPathAttr.attributeType = .stringAttributeType
		backdropPathAttr.isOptional = true
		properties.append(backdropPathAttr)
		
		let releaseDateAttr = NSAttributeDescription()
		releaseDateAttr.name = "releaseDate"
		releaseDateAttr.attributeType = .stringAttributeType
		releaseDateAttr.isOptional = true
		properties.append(releaseDateAttr)
		
		let popularityAttr = NSAttributeDescription()
		popularityAttr.name = "popularity"
		popularityAttr.attributeType = .doubleAttributeType
		popularityAttr.isOptional = true
		properties.append(popularityAttr)
		
		let voteAverageAttr = NSAttributeDescription()
		voteAverageAttr.name = "voteAverage"
		voteAverageAttr.attributeType = .doubleAttributeType
		voteAverageAttr.isOptional = true
		properties.append(voteAverageAttr)
		
		let voteCountAttr = NSAttributeDescription()
		voteCountAttr.name = "voteCount"
		voteCountAttr.attributeType = .integer64AttributeType
		voteCountAttr.isOptional = true
		properties.append(voteCountAttr)
		
		entity.properties = properties
		model.entities = [entity]
		return model
	}
	
	func newBackgroundContext() -> NSManagedObjectContext {
		let ctx = persistentContainer.newBackgroundContext()
		ctx.mergePolicy = NSMergeByPropertyObjectTrumpMergePolicy
		return ctx
	}
}

@objc(MovieEntity)
final class MovieEntity: NSManagedObject {
	@NSManaged var id: Int64
	@NSManaged var title: String
	@NSManaged var originalTitle: String?
	@NSManaged var overview: String?
	@NSManaged var posterPath: String?
	@NSManaged var backdropPath: String?
	@NSManaged var releaseDate: String?
	@NSManaged var popularity: Double
	@NSManaged var voteAverage: Double
	@NSManaged var voteCount: Int64
}