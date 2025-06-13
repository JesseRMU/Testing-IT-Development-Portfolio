<hr />
<details class="filter">
    <summary class="font-semibold">
        {{$name}}
    </summary>
    <div class="max-h-140 overflow-y-auto">
    @if($type == "checkbox")
        @if(isset($titles) && isset($titletable))
            @foreach(DB::table($table)->leftJoin($titletable, $table.'.'.$name, '=', $titletable.'.'.$name)->groupBy($name)->pluck($titles, $table.'.'.$name) as $value => $name_text)
                <input name="{{$name}}[]" value="{{$value ?? "null"}}" type="checkbox" id="{{$name}}_{{$value}}" @if( (!is_null(request($name))) && in_array($value ?? "null", request($name))) checked @endif />
                <label for="{{$name}}_{{$value}}">{{$name_text ?? $value ?? "(niet ingevuld)"}}</label><br>
            @endforeach
        @else
            @foreach(DB::table($table)->groupBy($name)->pluck($name) as $value)
                <input name="{{$name}}[]" value="{{$value ?? "null"}}" type="checkbox" id="{{$name}}_{{$value}}" @if( (!is_null(request($name))) && in_array($value ?? "null", request($name))) checked @endif />
                <label for="{{$name}}_{{$value}}">{{$value ?? "(niet ingevuld)"}}</label><br>
            @endforeach
        @endif
    @elseif($type == "number")
            <label for="{{$name}}_min">min:</label>
            <input name="{{$name}}[min]" value="{{request($name)["min"] ?? ""}}" placeholder="{{DB::table($table)->min($name)}}" type="number" id="{{$name}}_min" class="border-1" step="{{$step ?? 1}}" /><br>
            <label for="{{$name}}_max">max:</label>
            <input name="{{$name}}[max]" value="{{request($name)["max"] ?? ""}}" placeholder="{{DB::table($table)->max($name)}}" type="number" id="{{$name}}_max" class="border-1" step="{{$step ?? 1}}" /><br>
    @endif
    </div>
</details>
