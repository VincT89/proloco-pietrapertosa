"use client";

import React, { useState, useRef } from "react";
import { LogOut, Upload } from "lucide-react";
import { useStore } from "./Store";

export default function News() {
  const { posts, isAdmin, logout, addPost, addFotos } = useStore();
  const [postType, setPostType] = useState("news");
  const [title, setTitle] = useState("");
  const [date, setDate] = useState("");
  const [text, setText] = useState("");
  const [images, setImages] = useState([]);
  const [msg, setMsg] = useState("");
  const [warned, setWarned] = useState(false);
  const fileInputRef = useRef(null);

  const news = posts.filter((p) => p.type === "news").sort((a, b) => (b.date || "").localeCompare(a.date || "") || b.id - a.id);

  const handleFileChange = (e) => {
    setMsg("");
    const files = Array.from(e.target.files);
    if (!files.length) return;
    const big = files.find((f) => f.size > 1.5 * 1024 * 1024);
    if (big) {
      setMsg(`"${big.name}" è troppo grande: massimo 1,5 MB a immagine.`);
      e.target.value = "";
      return;
    }
    const newImages = [];
    let done = 0;
    files.forEach((f) => {
      const r = new FileReader();
      r.onload = () => {
        newImages.push(r.result);
        done++;
        if (done === files.length) {
          setImages(newImages);
        }
      };
      r.readAsDataURL(f);
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const t = title.trim();
    const x = text.trim();
    if (!t) return setMsg("Il titolo è obbligatorio.");
    if (postType !== "foto" && !x) return setMsg("Il testo è obbligatorio.");
    if (postType === "foto" && !images.length) return setMsg("Per la galleria serve almeno una foto.");
    if (postType === "evento" && !images.length && !warned) {
      setWarned(true);
      return setMsg("Per gli eventi è consigliata una locandina: caricala, oppure premi di nuovo 'Pubblica' per procedere senza.");
    }

    if (postType === "foto") {
      addFotos(t, images);
    } else {
      addPost({ type: postType, title: t, text: x, date, img: images[0] || "" });
    }

    setTitle("");
    setText("");
    setDate("");
    setImages([]);
    setWarned(false);
    if (fileInputRef.current) fileInputRef.current.value = "";
    setMsg(postType === "foto" ? "Foto aggiunte all'album, ben fatto!" : "Pubblicato — già in piazza!");
    setTimeout(() => setMsg(""), 3500);

    // Rilancia observer per i nuovi elementi creati
    setTimeout(() => {
      if (window.obsAll) window.obsAll();
    }, 100);
  };

  const formatDate = (d) => {
    if (!d) return "";
    return new Date(d + "T00:00").toLocaleDateString("it-IT", { day: "numeric", month: "long", year: "numeric" });
  };

  return (
    <section className="paper-sec" id="news">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">la bacheca del paese</span>
        </div>
        <h2 className="wr">
          Avvisi appesi <em>in piazza</em>
        </h2>

        {isAdmin && (
          <div className="adm-panel" id="admPanel" style={{ display: "block" }}>
            <div className="ah">
              <h3>
                Cosa pubblichiamo <em>oggi</em>?
              </h3>
              <button className="btn-quiet" onClick={logout}>
                <LogOut className="ico" style={{ width: 15, height: 15 }} /> Esci
              </button>
            </div>
            <form className="adm-form" onSubmit={handleSubmit}>
              <div>
                <label>Pubblica in *</label>
                <select value={postType} onChange={(e) => { setPostType(e.target.value); setImages([]); if (fileInputRef.current) fileInputRef.current.value = ""; }}>
                  <option value="news">News & Comunicazioni</option>
                  <option value="evento">Eventi (con locandina)</option>
                  <option value="foto">Galleria (foto degli eventi)</option>
                </select>
              </div>
              <div>
                <label>{postType === "foto" ? "Didascalia (per tutte le foto) *" : "Titolo *"}</label>
                <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} placeholder="Es. Sulle Tracce degli Arabi 2026" required />
              </div>
              <div>
                <label>Data</label>
                <input type="date" value={date} onChange={(e) => setDate(e.target.value)} />
              </div>
              <div className="full" style={{ display: postType === "foto" ? "none" : "block" }}>
                <label>Testo *</label>
                <textarea value={text} onChange={(e) => setText(e.target.value)} placeholder="Scrivi qui il contenuto..."></textarea>
              </div>
              <div className="full">
                <label>{postType === "foto" ? "Foto (anche più di una)" : "Locandina / manifesto / foto (consigliata per gli eventi)"}</label>
                <label className="filebox">
                  <Upload className="ico" />
                  <span>{images.length > 0 ? (images.length === 1 ? "Foto caricata" : `Caricate ${images.length} foto`) : "Clicca per caricare — JPG/PNG, max ~1,5 MB l'una"}</span>
                  <input type="file" ref={fileInputRef} accept="image/*" multiple={postType === "foto"} onChange={handleFileChange} />
                </label>
              </div>
              {postType === "foto" && (
                <div className="full" style={{ fontSize: ".78rem", color: "var(--stone)", border: "1px dashed var(--hair)", padding: "12px 16px" }}>
                  Per la galleria puoi selezionare <b style={{ color: "var(--gold-soft)" }}>più foto insieme</b>: il titolo diventa la didascalia di tutte.
                </div>
              )}
              <div className="adm-actions">
                <button type="submit" className="btn-pub">Pubblica</button>
              </div>
              <div className="adm-msg">{msg}</div>
            </form>
          </div>
        )}

        <div className="news-grid">
          {news.map((n) => (
            <article key={n.id} className="nc">
              {n.img && (
                <div className="thumb cur" data-zoom={n.img} data-cap={n.title}>
                  <img src={n.img} alt={n.title} loading="lazy" />
                </div>
              )}
              <div className="body">
                {n.date && <span className="date">{formatDate(n.date)}</span>}
                <h3 className="serif">{n.title}</h3>
                <p style={{ whiteSpace: "pre-line" }}>{n.text}</p>
              </div>
            </article>
          ))}
        </div>
        {news.length === 0 && (
          <div className="news-empty" style={{ display: "block" }}>
            Nessuna news pubblicata al momento — torna presto a trovarci.
          </div>
        )}
      </div>
    </section>
  );
}