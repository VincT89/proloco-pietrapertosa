"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { createContext, useContext, useState, useEffect } from "react";

const StoreContext = createContext(null);

const seed = [
  {
    id: 1,
    type: "evento",
    title: "Sulle Tracce degli Arabi",
    date: "2026-08-10",
    text: "10 e 11 agosto, come ogni anno: l'Arabata torna all'838 con danze, fachiri, mangiafuoco e i mercatini tra i vicoli. La festa che prepariamo tutto l'anno — venite, che si balla.",
    img: "/images/arabata01.jpg",
  },
  {
    id: 2,
    type: "evento",
    title: "Il Maggio di Pietrapertosa",
    date: "2026-06-13",
    text: 'Festa di Sant\'Antonio. L\'antico rito arboreo: il "matrimonio" tra il Maggio e la Cima, portati dal bosco e innalzati in paese tra musica e festa collettiva.',
    img: "/images/sanCataldo.jpg",
  },
  {
    id: 3,
    type: "evento",
    title: "Carnevale & Sagra della Rafanata",
    date: "2026-02-17",
    text: "Il Martedì Grasso i bambini girano per le case col cupa cupa, sfilano le maschere e si mangia la rafanata, la frittata al rafano. Venite affamati.",
    img: "/images/immaginePaese.jpg",
  },
  {
    id: 4,
    type: "evento",
    title: "Sapori d'Autunno",
    date: "2026-10-25",
    text: "Castagne, funghi e i piatti identitari pietrapertosani negli stand lungo le vie del borgo, con il mercato dei prodotti tipici e la musica popolare.",
    img: "/images/castello.jpg",
  },
  {
    id: 5,
    type: "news",
    title: "Cercasi volontari per la festa d'agosto",
    date: "2026-05-12",
    text: "Servono mani per montare il palco, allestire l'Arabata e dare una mano agli stand durante Sulle Tracce degli Arabi. Chi vuole partecipare può scriverci o passare in sede.",
    img: "/images/sanGiacomo.jpg",
  },
  {
    id: 6,
    type: "news",
    title: "Nuovi orari del punto informazioni",
    date: "2026-06-01",
    text: "Da giugno il punto informazioni turistiche della Pro Loco è aperto tutti i giorni, mattina e pomeriggio. Vi aspettiamo per mappe, consigli e prenotazioni delle visite guidate.",
    img: "/images/immaginePaese.jpg",
  },
  { id: 7, type: "foto", title: "Il Castello sulla rupe", img: "/images/castello.jpg" },
  {
    id: 8,
    type: "foto",
    title: "San Giacomo e il borgo",
    img: "/images/sanGiacomo.jpg",
  },
  { id: 9, type: "foto", title: "Pietra e cielo", img: "/images/immaginePaese.jpg" },
  {
    id: 10,
    type: "foto",
    title: "I vicoli dell'Arabata",
    img: "/images/arabata01.jpg",
  },
  {
    id: 11,
    type: "foto",
    title: "La torre del Castello",
    img: "/images/torre.jpg",
  },
  { id: 12, type: "foto", title: "Le vie ferrate", img: "/images/viaFerrata.jpg" },
  { id: 13, type: "foto", title: "Percorsi nel bosco", img: "/images/percorsi.jpg" },
  { id: 14, type: "foto", title: "Panorama Arabata", img: "/images/arabata.jpeg" },
  { id: 15, type: "foto", title: "Scorci del borgo", img: "/images/immaginePaese.jpg" },
];

export function StoreProvider({ children }) {
  const [posts, setPosts] = useState(seed);
  const [isAdmin, setIsAdmin] = useState(false);
  const [lightboxImg, setLightboxImg] = useState(null);
  const [isLoginModalOpen, setIsLoginModalOpen] = useState(false);

  useEffect(() => {
    try {
      const stored = localStorage.getItem("pl_posts_v8");
      if (stored) {
        setPosts(safeJsonParse(stored));
      } else {
        localStorage.setItem("pl_posts_v8", JSON.stringify(seed));
      }
      if (sessionStorage.getItem("pl_adm") === "1") {
        setIsAdmin(true);
      }
    } catch (e) {}
  }, []);

  const savePosts = (newPosts) => {
    try {
      localStorage.setItem("pl_posts_v8", JSON.stringify(newPosts));
      setPosts(newPosts);
      return true;
    } catch (e) {
      return false;
    }
  };

  const login = (password) => {
    if (password === "proloco2026") {
      setIsAdmin(true);
      sessionStorage.setItem("pl_adm", "1");
      return true;
    }
    return false;
  };

  const logout = () => {
    setIsAdmin(false);
    sessionStorage.removeItem("pl_adm");
  };

  const addPost = (post) => {
    const newPosts = [...posts, { ...post, id: Date.now() }];
    savePosts(newPosts);
  };

  const addFotos = (title, images) => {
    const newPosts = [...posts];
    images.forEach((img, i) => {
      newPosts.push({ id: Date.now() + i, type: "foto", title, img });
    });
    savePosts(newPosts);
  };

  return (
    <StoreContext.Provider
      value={{
        posts,
        isAdmin,
        login,
        logout,
        addPost,
        addFotos,
        lightboxImg,
        openLightbox: (srcOrArray, indexOrCap) => {
          if (Array.isArray(srcOrArray)) {
            setLightboxImg({ isGallery: true, images: srcOrArray, currentIndex: indexOrCap || 0 });
          } else {
            setLightboxImg({ isGallery: false, src: srcOrArray, cap: indexOrCap });
          }
        },
        closeLightbox: () => setLightboxImg(null),
        isLoginModalOpen,
        openLoginModal: () => setIsLoginModalOpen(true),
        closeLoginModal: () => setIsLoginModalOpen(false),
      }}
    >
      {children}
    </StoreContext.Provider>
  );
}

export const useStore = () => {
  const context = useContext(StoreContext);
  if (!context) throw new Error("useStore must be used within StoreProvider");
  return context;
};
