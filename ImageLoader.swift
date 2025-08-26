import Foundation
import SwiftUI

final class ImageCache {
	static let shared = ImageCache()
	private let cache = NSCache<NSURL, UIImage>()
	
	func image(for url: NSURL) -> UIImage? { cache.object(forKey: url) }
	func insert(_ image: UIImage, for url: NSURL) { cache.setObject(image, forKey: url) }
}

@MainActor
final class AsyncImageLoader: ObservableObject {
	@Published var image: UIImage?
	private var task: Task<Void, Never>?
	
	func load(url: URL?) {
		task?.cancel()
		guard let url = url else { self.image = nil; return }
		let nsurl = url as NSURL
		if let cached = ImageCache.shared.image(for: nsurl) {
			self.image = cached
			return
		}
		task = Task {
			do {
				let (data, _) = try await URLSession.shared.data(from: url)
				if let ui = UIImage(data: data) {
					ImageCache.shared.insert(ui, for: nsurl)
					self.image = ui
				}
			} catch { }
		}
	}
	
	func cancel() { task?.cancel() }
}

struct URLImageView: View {
	let url: URL?
	let placeholder: String
	@StateObject private var loader = AsyncImageLoader()
	
	var body: some View {
		Group {
			if let img = loader.image {
				Image(uiImage: img).resizable().scaledToFill()
			} else {
				ZStack { Color.gray.opacity(0.1); Text(placeholder).font(.caption).multilineTextAlignment(.center).padding(4) }
			}
		}
		.onAppear { loader.load(url: url) }
		.onDisappear { loader.cancel() }
	}
}