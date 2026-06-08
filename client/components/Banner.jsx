export default function Banner() {
  return (
    <section className="banner">
      <div
        className="bgi"
        id="bannerBg"
        style={{
          backgroundImage:
            "url('https://commons.wikimedia.org/wiki/Special:FilePath/Mura%20di%20una%20torre%20del%20Castello%20di%20Pietrapertosa.jpg?width=2000')",
        }}
      ></div>

      <div className="cap">
        <div className="serif">«Le mura del Castello, vedetta sul mondo da mille anni»</div>
        <span className="lbl-mut">Dolomiti Lucane · Parco di Gallipoli Cognato</span>
      </div>
    </section>
  );
}