<html>
    <head>
        <title>Photo Prompt for {{ $title }} @ My Writing Notebook</title>
    </head>
    <body>
    <h1 style="text-align:center; font-family:sans-serif; ">
        <a href="{{ url('view/'.$prev->year.'/'.$prev->month) }}" style="float:left;border-radius: 100px; border: 1px solid #000; padding: 5px 8px; background: rgba(255,255,255,.5);color: #000; text-decoration:none;">&larr;</a>
        {{ $title }}
        <a href="{{ url('view/'.$next->year.'/'.$next->month) }}" style="float:right;border-radius: 100px; border: 1px solid #000; padding: 5px 8px; background: rgba(255,255,255,.5); color: #000; text-decoration:none;">&rarr;</a>
        
    </h1>
    
    <table width="100%">
    <tbody>
    @foreach($photos as $week)
        <tr>
            @foreach($week as $photo)
                @if($photo != null)
                <td width="14%" 
                    style="background: url({{ $photo->image }}) center center;">
                    <div class="photo" style="width:100%">
                        <a href="{{ $photo->link }}" style="text-decoration: none; color: rgba(0,0,0,.75); display: block; width:100%; height:150px; padding: 30px; text-align: center; position: relative; box-sizing: border-box;">
                            <span style="font-weight: bold; font-size: 30pt; display:block; position: absolute; top: 0px; right: 0px; border-radius: 100px; border: 1px solid #000; padding: 5px 8px; background: rgba(255,255,255,.5);">
                                {{ date('d',strtotime($photo->date)) }}
                            </span>
                        </a>
                    </div>
                </td>
                @else
                <td width="14%">&nbsp;</td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
    </table>

</body>
</html>