@props(['product'])

<div id="reviewModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create Review</h2>
            <span class="close-modal">&times;</span>
        </div>
        
        <form action="{{ route('ratings.store') }}" method="POST">
            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="form-group">
                <label class="modal-label">Overall rating</label>
                <select name="stars" class="qty-select w100">
                    <option value="5">5 Stars - Amazing</option>
                    <option value="4.5">4.5 Stars - Excellent</option>
                    <option value="4">4 Stars - Very Good</option>
                    <option value="3.5">3.5 Stars - Good</option>
                    <option value="3">3 Stars - Average</option>
                    <option value="2.5">2.5 Stars - Decent</option>
                    <option value="2">2 Stars - Poor</option>
                    <option value="1.5">1.5 Stars - Really Poor</option>
                    <option value="1">1 Star - Terrible</option>
                    <option value="0.5">0.5 Stars - Horrible</option>
                </select>
            </div>

            <div class="form-group">
                <label class="modal-label">Add a Review</label>
                <textarea 
                    name="comment" 
                    rows="4" 
                    maxlength="1000"
                    class="review-input" 
                    placeholder="What did you like or dislike?"
                    ></textarea>
            </div>

            <button type="submit" class="button-review w100">Submit Review</button>
        </form>
    </div>
</div>