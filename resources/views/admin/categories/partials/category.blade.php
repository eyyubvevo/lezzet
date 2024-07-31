<li data-nodeid="{{$category->id}}">
    <form action="{{route('admin.categories.delete', ['id' => $category->id])}}" method="POST" style="display:inline;float: right">
        {{csrf_field()}}
        {{ method_field('DELETE')}}
        <button type="submit" class=""><i style="color: red;" class="bx bx-trash me-2"></i></button>
    </form>
    <a href="{{route('admin.categories.edit', ['id' => $category->id])}}" style="color: #0d6efd; float: right;" ><i class="bx bx-edit-alt me-2"></i></a>
    <a href="#"> {{ $category->getTranslation('name', 'az') }}</a>
    @if($category->children->isNotEmpty())
        <ul class="list-group">
            @each('admin.categories.partials.category', $category->children, 'category')
        </ul>
    @endif
</li>
