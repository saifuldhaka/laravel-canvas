@extends('layout.mainlayout')
@section('script')
<script type="module">
  import 'https://cdn.interactjs.io/v1.9.20/auto-start/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/actions/drag/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/actions/resize/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/modifiers/index.js'
  import 'https://cdn.interactjs.io/v1.9.20/dev-tools/index.js'
  import interact from 'https://cdn.interactjs.io/v1.9.20/interactjs/index.js'


  // target elements with the "draggable" class
  interact('.draggable')
    .draggable({
      // enable inertial throwing
      inertia: true,
      // keep the element within the area of it's parent
      modifiers: [
        interact.modifiers.restrictRect({
          restriction: 'parent',
          endOnly: true
        })
      ],
      // enable autoScroll
      autoScroll: true,

      listeners: {
        // call this function on every dragmove event
        move: dragMoveListener,

        // call this function on every dragend event
        end (event) {
          // var textEl = event.target.querySelector('p')
          //
          // textEl && (textEl.textContent =
          //   'moved a distance of ' +
          //   (Math.sqrt(Math.pow(event.pageX - event.x0, 2) +
          //              Math.pow(event.pageY - event.y0, 2) | 0))
          //     .toFixed(2) + 'px')
        }
      }
    })


  function dragMoveListener (event) {
    var target = event.target
    // keep the dragged position in the data-x/data-y attributes
    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

    // translate the element
    target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'

    // update the posiion attributes
    target.setAttribute('data-x', x)
    target.setAttribute('data-y', y)
  }

  // this function is used later in the resizing and gesture demos
  window.dragMoveListener = dragMoveListener

</script>
@endsection

@section('content')

   <div class="album text-muted">
     <div class="container">
       <div class="row">
         <div class="col-sm-12">
           <h1 class="display-4">Create Canvas</h1>
         </div>
         <div class="col-sm-8 canvas-content" >
           <div id="canvas-content-holder" >
              <div class="draggable">
                <p> {{ $identifier->name }} </p>
              </div>
              <div class="draggable resize-drag">
                <p> {{ $identifier->country }} </p>
              </div>
              <div class="draggable resize-drag">
                <p> {{ $identifier->organization }} </p>
              </div>
              <div class="draggable resize-drag">
                <img src="{{ url('image/'.$identifier->photo) }}" alt="sample image" id="scale-element" >
              </div>
           </div>
         </div>
         <div class="col-sm-4" style="min-height: 500px; border: 1px solid #ccc; ">
           <h1 class="display-6">Save Canvas</h1>
           @if ($errors->any())
             <div class="alert alert-danger">
               <ul>
               @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
               @endforeach
               </ul>
             </div><br />
           @endif

           <form id="form-canvas" method="post" action="{{ route('canvas.store') }}" enctype="multipart/form-data">
             @csrf
             <div class="form-group">
               <label for="first_name">Canvas Name:</label>
               <input type="text" class="form-control" name="name" required/>
             </div>

             <div class="form-group">
               <label for="user_name">Identifier name:</label>
               <input type="text" class="form-control" name="user_name" value="{{ $identifier->name }}" readonly/>
             </div>

             <div class="form-group">
               <label for="country">Country:</label>
               <input type="text" class="form-control" name="country" value="{{ $identifier->country }}" readonly/>
             </div>

             <div class="form-group">
               <label for="organization">Organization:</label>
               <input type="text" class="form-control" name="organization" value="{{ $identifier->organization }}" readonly/>
             </div>

             <input type="hidden" class="form-control" name="user_id" value="{{ $identifier->id }}"/>
             <input type="hidden" class="form-control" id="html_canvas" name="html_canvas" />
             <button type="button" class="btn btn-primary-outline" onClick="convertBase64()">Save Canvas</button>
           </form>
         </div>
       </div>
     </div>
   </div>


   <script>

   function convertBase64(){
      html2canvas([document.getElementById('canvas-content-holder')], {
        onrendered: function(canvas) {
          var data = canvas.toDataURL('image/png');
          var image = new Image();
          image.src = data;
          $('#html_canvas').val(image.src);
          $( "#form-canvas" ).submit();
        }
      });
   }

   </script>

@endsection
