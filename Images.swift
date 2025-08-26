import SwiftUI

final class ImageCache {
	static let shared = ImageCache()
	private init() {}
	private var cache: NSCache<NSURL, UIImage> = NSCache()

	func image(for url: URL) -> UIImage? { cache.object(forKey: url as NSURL) }
	func insert(_ image: UIImage, for url: URL) { cache.setObject(image, forKey: url as NSURL) }
}

struct RemoteImage: View {
	let url: URL?
	let placeholder: AnyView

	@State private var uiImage: UIImage?

	init(url: URL?, placeholder: @escaping () -> some View = { ProgressView() }) {
		self.url = url
		self.placeholder = AnyView(placeholder())
	}

	var body: some View {
		Group {
			if let image = uiImage {
				Image(uiImage: image)
					.resizable()
					.scaledToFill()
			} else {
				placeholder
			}
		}
		.onAppear { load() }
	}

	private func load() {
		guard let url = url else { return }
		if let cached = ImageCache.shared.image(for: url) {
			self.uiImage = cached
			return
		}
		Task {
			if let (data, _) = try? await URLSession.shared.data(from: url), let image = UIImage(data: data) {
				ImageCache.shared.insert(image, for: url)
				await MainActor.run { self.uiImage = image }
			}
		}
	}
}