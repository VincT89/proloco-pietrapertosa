"use client";

import React from "react";
import { useSearchParams } from 'next/navigation';
import { getHomeData } from '@/data/home';
import { getLang } from '@/utils/lang';

export default function Hero() {
  const searchParams = useSearchParams();
  const lang = getLang(searchParams);
  const isEn = lang === 'en';
  const data = getHomeData(lang);

  return (
    <header className="hero" id="top">
      <div className="hero-media" id="heroMedia">
        <div
          className="hero-bg"
          style={{ backgroundImage: `linear-gradient(to bottom, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('${data.hero.img}')` }}
        ></div>
      </div>
      <div className="hero-in">
        <h1 className="hero-h1">
          <span className="ln">
            <span>{isEn ? "Pietrapertosa is alive" : "Pietrapertosa vive"}</span>
          </span>
          <span className="ln">
            <span>
              {isEn ? <em>all year round</em> : <><em className="hero-em">tutto</em> {"l'"}<em>anno</em></>}
            </span>
          </span>
        </h1>
        <div className="hero-foot">
          <p className="hero-desc">
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