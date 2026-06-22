<div class="exp-row {{ ($reverse ?? false) ? 'rev' : '' }}">
    <div class="exp-img cur" onclick="openGallery(['{{ $img }}'])">
        <img class="bgi-img" src="{{ $img }}" alt="" />
    </div>
    <div class="exp-txt">
        @if(isset($subtitle) && $subtitle)
            <span class="lbl-mut fad">{{ $subtitle }}</span>
        @endif
        <h3 class="wr">{{ $title }}</h3>
        <p class="fad">{!! clean($desc ?? '') !!}</p>
        
        @if(isset($stats) && count($stats) > 0)
            <div class="exp-data fad">
                @foreach($stats as $key => $stat)
                    @php
                        $val = is_array($stat) ? ($stat['value'] ?? '') : $stat;
                        $lbl = is_array($stat) ? ($stat['label'] ?? '') : (is_numeric($key) ? '' : $key);
                    @endphp
                    <div>
                        <div class="v">{{ $val }}</div>
                        <div class="c">{{ $lbl }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
