"use client";

import React from "react";
import { usePathname } from 'next/navigation';

export default function Hero() {
  const pathname = usePathname() || '';
  const isEn = pathname.startsWith('/en');

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
            <span>{isEn ? "Pietrapertosa is alive" : "Pietrapertosa vive"}</span>
          </span>
          <span className="ln">
            <span>
              {isEn ? <em>all year round</em> : <><em style={{marginRight: '8px'}}>tutto</em> {"l'"}<em>anno</em></>}
            </span>
          </span>
        </h1>
        <div className="hero-foot">
          <p>
            {isEn ? "Events, traditions, associations, people and initiatives that animate the village." : "Eventi, tradizioni, associazioni, persone e iniziative che animano il paese."}
          </p>
        </div>
      </div>
      <div className="hero-scroll">
        <i></i>
      </div>
    </header>
  );
}