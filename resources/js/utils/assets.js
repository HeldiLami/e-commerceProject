export function getAssetUrl(path) {
    const cleanPath = path.startsWith('/') ? path.slice(1) : path;
    
    if (typeof window !== 'undefined' && window.assetBaseUrl) {
        return window.assetBaseUrl + cleanPath;
    }

    return '/' + cleanPath;
}