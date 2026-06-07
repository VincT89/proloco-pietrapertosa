export default function Manifesto() {
  return (
    <section className="mani">
      <div className="narrow">
        <span className="lbl fad">chi siamo, in due parole</span>

        <h2 className="wr">
          Un paese di pietra viva, dove le case nascono dalla roccia e il vento racconta storie di{" "}
          <em>saraceni</em>, <em>normanni</em> e <em>angeli in volo</em>.
        </h2>

        <div className="row">
          <div className="cell fad">
            <div className="num" data-cnt="1088">0</div>
            <div className="cap lbl-mut">metri sul mare, mica pochi</div>
          </div>

          <div className="cell fad">
            <div className="num">
              <span data-cnt="838">0</span><sup> d.C.</sup>
            </div>
            <div className="cap lbl-mut">quando arrivarono gli arabi</div>
          </div>

          <div className="cell fad">
            <div className="num" data-cnt="1063">0</div>
            <div className="cap lbl-mut">abitanti</div>
          </div>

          <div className="cell fad">
            <div className="num">
              <span data-cnt="120">0</span><sup> km/h</sup>
            </div>
            <div className="cap lbl-mut">in volo sull&apos;Angelo</div>
          </div>
        </div>
      </div>
    </section>
  );
}