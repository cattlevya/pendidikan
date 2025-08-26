import CoreData

final class PersistenceController {
	static let shared = PersistenceController()

	let container: NSPersistentContainer

	private init(inMemory: Bool = false) {
		let model = PersistenceController.createModel()
		container = NSPersistentContainer(name: "MovieModel", managedObjectModel: model)

		if inMemory {
			let description = NSPersistentStoreDescription()
			description.type = NSInMemoryStoreType
			container.persistentStoreDescriptions = [description]
		}

		container.loadPersistentStores { _, error in
			if let error = error as NSError? {
				fatalError("Unresolved error: \(error), \(error.userInfo)")
			}
		}
		container.viewContext.mergePolicy = NSMergeByPropertyObjectTrumpMergePolicy
		container.viewContext.automaticallyMergesChangesFromParent = true
	}

	private static func createModel() -> NSManagedObjectModel {
		let model = NSManagedObjectModel()

		let movieEntity = NSEntityDescription()
		movieEntity.name = "MovieEntity"
		movieEntity.managedObjectClassName = NSStringFromClass(MovieEntity.self)

		var properties: [NSAttributeDescription] = []

		let idAttr = NSAttributeDescription()
		idAttr.name = "id"
		idAttr.attributeType = .integer64AttributeType
		idAttr.isOptional = false
		properties.append(idAttr)

		let titleAttr = NSAttributeDescription()
		titleAttr.name = "title"
		titleAttr.attributeType = .stringAttributeType
		titleAttr.isOptional = true
		properties.append(titleAttr)

		let overviewAttr = NSAttributeDescription()
		overviewAttr.name = "overview"
		overviewAttr.attributeType = .stringAttributeType
		overviewAttr.isOptional = true
		properties.append(overviewAttr)

		let releaseDateAttr = NSAttributeDescription()
		releaseDateAttr.name = "releaseDate"
		releaseDateAttr.attributeType = .stringAttributeType
		releaseDateAttr.isOptional = true
		properties.append(releaseDateAttr)

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

		movieEntity.properties = properties
		model.entities = [movieEntity]
		return model
	}
}

@objc(MovieEntity)
final class MovieEntity: NSManagedObject {
	@NSManaged var id: Int64
	@NSManaged var title: String?
	@NSManaged var overview: String?
	@NSManaged var releaseDate: String?
	@NSManaged var posterPath: String?
	@NSManaged var backdropPath: String?
	@NSManaged var popularity: Double
	@NSManaged var voteAverage: Double
	@NSManaged var voteCount: Int64
}