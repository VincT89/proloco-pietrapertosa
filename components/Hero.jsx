"use client";

import React from "react";

export default function Hero() {
  return (
    <header className="hero" id="top">
      <div className="hero-media" id="heroMedia">
        <div
          className="hero-bg"
          style={{ backgroundImage: "url('/images/immaginePaese.jpg')" }}
        ></div>
      </div>
      <div className="hero-in">
        <h1>
          <span className="ln">
            <span>Pietrapertosa vive</span>
          </span>
          <span className="ln">
            <span>
              <em>tutto</em> l'<em>anno</em>
            </span>
          </span>
        </h1>
        <div className="hero-foot">
          <p>
            Eventi, tradizioni, associazioni, persone e iniziative che animano il paese.
          </p>
        </div>
      </div>
      <div className="hero-scroll">
        <i></i>
      </div>
    </header>
  );
}