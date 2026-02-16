@extends('layouts.app')

@section('title', 'Reply')
@section('sub_title', 'Reply add or modification form')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="col-md-8">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($user->id))
                        reply_forms
                    @else
                        reply_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($reply->id))
                        Edit reply
                    @else
                        Add a new reply
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($reply->id))
                        reply/{{$reply->id}}/update
                    @else
                        reply_save
                    @endif
                @endslot

                @slot('fields')

                    <?php
                    dump($comment);
                    ?>

                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('item_id', (!empty($comment->item_id) ? $comment->item_id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('comment_on', (!empty($comment->comment_on) ? $comment->comment_on : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('parent_id', (!empty($comment->id) ? $comment->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('is_active', (!empty($comment->is_active) ? $comment->is_active : 1), ['type' => 'hidden']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('reply', 'Reply', array('class' => 'reply')) }}
                        {{ Form::textarea('comment', (!empty($reply->description) ? $reply->description : NULL), ['required', 'class' => 'form-control', 'id' => 'wysiwyg', 'placeholder' => 'Enter details content...']) }}
                    </div>

                @endslot
            @endcomponent
        </div>
    </div>
@endsection