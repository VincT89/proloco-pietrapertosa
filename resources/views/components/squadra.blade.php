@php
    

    $membri = [
      ['nome' => "Laraia Marinella", 'ruolo' => (app()->getLocale() === 'en') ? "PRESIDENT" : "PRESIDENTE"],
      ['nome' => "Lucia Zottarelli", 'ruolo' => (app()->getLocale() === 'en') ? "VICE PRESIDENT" : "VICE PRESIDENTE"],
      ['nome' => "Marilina Giannotta", 'ruolo' => (app()->getLocale() === 'en') ? "SECRETARY" : "SEGRETARIO"],
      ['nome' => "Marianna Giannotta", 'ruolo' => (app()->getLocale() === 'en') ? "TREASURER" : "TESORIERE"],
      ['nome' => "Marinella Pellettiere", 'ruolo' => (app()->getLocale() === 'en') ? "COUNCILOR" : "CONSIGLIERE"]
    ];
@endphp

<section id="squadra">
    <div class="wrap">
        <div class="sec-top">
            <span class="lbl">{{ (app()->getLocale() === 'en') ? "the faces of Pro Loco" : "le facce della Pro Loco" }}</span>
        </div>

        <h2 class="wr sq-title">
            {!! (app()->getLocale() === 'en') ? "The ones who set up <em>the stage</em>" : "Quelli che montano <em>il palco</em>" !!}
        </h2>

        <div class="team-due">
            <figure class="team-foto cur in" id="teamPhoto">
                <img
                    src="{{ asset('images/squadra1.jpg') }}"
                    onclick="openGallery(['{{ asset('images/squadra1.jpg') }}'])"
                    class="sq-img"
                    alt="{{ (app()->getLocale() === 'en') ? 'The Pro Loco Board' : 'Il Direttivo Pro Loco' }}"
                />
                <figcaption>{{ (app()->getLocale() === 'en') ? "The complete team — Pro Loco volunteers" : "La squadra al completo — i volontari della Pro Loco" }}</figcaption>
            </figure>

            <div class="team-lista">
                @foreach($membri as $m)
                    <div class="riga-m fad">
                        <span class="ruolo">{{ $m['ruolo'] }}</span>
                        <span class="nome-m">{{ $m['nome'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
