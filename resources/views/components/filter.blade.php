<hr />
<details class="filter">
    <summary class="font-semibold">
        {{$name}}
    </summary>
    @if($type == "checkbox")
        @if(isset($titles) && isset($titletable))
            @foreach(DB::table($table)->leftJoin($titletable, $table.'.'.$name, '=', $titletable.'.'.$name)->groupBy($name)->pluck($titles, $table.'.'.$name) as $value => $name_text)
                <input name="{{$name}}[]" value="{{$value}}" type="checkbox" id="{{$name}}_{{$value}}" @if( (!is_null(request($name))) && in_array($value, request($name))) checked @endif />
                <label for="{{$name}}_{{$value}}">{{$name_text ?? $value}}</label><br>
            @endforeach
        @else
            @foreach(DB::table($table)->groupBy($name)->pluck($name) as $value)
                <input name="{{$name}}[]" value="{{$value}}" type="checkbox" id="{{$name}}_{{$value}}" @if( (!is_null(request($name))) && in_array($value, request($name))) checked @endif />
                <label for="{{$name}}_{{$value}}">{{$value}}</label><br>
            @endforeach
        @endif
    @endif
</details>
