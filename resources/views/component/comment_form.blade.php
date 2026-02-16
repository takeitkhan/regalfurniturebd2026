<div class="row">
    <div class="comment-box">

        {{ Form::open(array('url' => 'comment_save', 'method' => 'post', 'value' => 'PATCH', 'id' => 'comment-id')) }}
        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
        {{ Form::hidden('item_id', (!empty($product->id) ? $product->id : NULL), ['type' => 'hidden']) }}
        {{ Form::hidden('comment_on', 'products', ['type' => 'hidden']) }}


        <div class="col-md-6">
            <h4>Ask your question or write your comment about this product</h4>
            <div class="single-comment-bx">
                {{ Form::text('name', NULL, ['required', 'class' => 'form-control', 'placeholder' => 'Enter your name...']) }}
            </div>
            <div class="single-comment-bx">
                {{ Form::text('email', NULL, ['required', 'class' => 'form-control', 'placeholder' => 'Enter your email...']) }}
            </div>
            <div class="single-comment-bx">
                {{ Form::textarea('comment', NULL, ['required', 'class' => 'form-control', 'id' => 'comment', 'placeholder' => 'Write your comment...']) }}
            </div>

            <div id="show_message"></div>
        </div>
        <div class="col-md-6">
            <p>
                <strong>Note: </strong>
                Your comment will be moderated and approved within 24 hours unless found inappropriate. Editors retain
                discretion over their publication. Because of our policy to keep all content on Citizen Matters free of
                access restrictions, postings need moderation to keep spammers and net abusers away. On the other hand,
                you can not modify your comment later. We do hope you will understand this.
            </p>
        </div>
        <div class="col-md-12">
            <div class="remamber-btn">
                {{ Form::submit('Comment', ['class' => 'btn btn-primary', 'id' => 'comment_form']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>