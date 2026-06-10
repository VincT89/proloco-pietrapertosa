"use client";

import React, { useState, useEffect } from 'react';
import Card from '../components/ui/Card';
import Input from '../components/ui/Input';
import Textarea from '../components/ui/Textarea';
import Button from '../components/ui/Button';
import Tabs from '../components/ui/Tabs';
import Select from '../components/ui/Select';
import { Save } from 'lucide-react';

export default function PagesAdmin() {
  const [selectedPage, setSelectedPage] = useState('territorio');
  const [formData, setFormData] = useState({
    hero_title: '', hero_title_en: '',
    hero_subtitle: '', hero_subtitle_en: '',
    hero_image_url: '',
    intro_title: '', intro_title_en: '',
    intro_text: '', intro_text_en: ''
  });
  const [mediaList, setMediaList] = useState([]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  const pageOptions = [
    { value: 'territorio', label: 'Il Territorio' },
    { value: 'sapori', label: 'I Sapori e Prodotti' }
  ];

  useEffect(() => {
    const fetchMedia = async () => {
      try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/media`, {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('admin_token')}` }
        });
        if (res.ok) {
          const data = await res.json();
          setMediaList(data.media);
        }
      } catch (err) { console.error(err); }
    };
    fetchMedia();
  }, []);

  useEffect(() => {
    const fetchPageData = async () => {
      setLoading(true);
      try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/pages/${selectedPage}`);
        if (res.ok) {
          const data = await res.json();
          setFormData({
            hero_title: data.page.hero_title || '',
            hero_title_en: data.page.hero_title_en || '',
            hero_subtitle: data.page.hero_subtitle || '',
            hero_subtitle_en: data.page.hero_subtitle_en || '',
            hero_image_url: data.page.hero_image_url || '',
            intro_title: data.page.intro_title || '',
            intro_title_en: data.page.intro_title_en || '',
            intro_text: data.page.intro_text || '',
            intro_text_en: data.page.intro_text_en || ''
          });
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };
    
    fetchPageData();
  }, [selectedPage]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const token = localStorage.getItem('admin_token');
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/pages/${selectedPage}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(formData)
      });
      if (res.ok) {
        alert("Testi aggiornati con successo!");
      } else {
        throw new Error("Errore durante il salvataggio");
      }
    } catch (err) {
      alert(err.message);
    } finally {
      setSaving(false);
    }
  };

  const tabIT = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (IT)" name="hero_title" value={formData.hero_title} onChange={handleChange} required />
        <Input label="Sottotitolo (IT)" name="hero_subtitle" value={formData.hero_subtitle} onChange={handleChange} />
      </div>
      <div className="grid-2">
        <Input label="Titolo Introduzione (IT)" name="intro_title" value={formData.intro_title} onChange={handleChange} />
      </div>
      <Textarea label="Testo Introduzione (IT)" name="intro_text" value={formData.intro_text} onChange={handleChange} rows={4} />
    </div>
  );

  const tabEN = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (EN)" name="hero_title_en" value={formData.hero_title_en} onChange={handleChange} />
        <Input label="Sottotitolo (EN)" name="hero_subtitle_en" value={formData.hero_subtitle_en} onChange={handleChange} />
      </div>
      <div className="grid-2">
        <Input label="Titolo Introduzione (EN)" name="intro_title_en" value={formData.intro_title_en} onChange={handleChange} />
      </div>
      <Textarea label="Testo Introduzione (EN)" name="intro_text_en" value={formData.intro_text_en} onChange={handleChange} rows={4} />
    </div>
  );

  const mediaOptions = [
    { value: '', label: 'Scegli dalla Media Library...' },
    ...mediaList.map(m => ({
      value: m.url,
      label: `${m.folder}/${m.public_id.split('/').pop()}`
    }))
  ];

  return (
    <div className="max-w-1000px">
      <div className="admin-page-header mb-24px">
        <h1 className="admin-page-title">Gestione Pagine <span>testi statici</span></h1>
        <div className="w-250px">
          <Select 
            value={selectedPage} 
            onChange={(e) => setSelectedPage(e.target.value)}
            options={pageOptions}
          />
        </div>
      </div>

      {loading ? (
        <div className="admin-loading-text">Caricamento dati...</div>
      ) : (
        <form onSubmit={handleSubmit} className="flex-col">
          <Card>
            <div className="flex-col">
              <label className="admin-form-label">Immagine di Copertina (Hero)</label>
              <div className="flex-row">
                <Select 
                  value={mediaList.find(m => m.url === formData.hero_image_url) ? formData.hero_image_url : ''}
                  onChange={(e) => setFormData({...formData, hero_image_url: e.target.value})}
                  options={mediaOptions}
                  className="flex-1"
                />
                <Input 
                  type="text" name="hero_image_url" value={formData.hero_image_url} onChange={handleChange} placeholder="Oppure incolla URL qui"
                  className="flex-2"
                />
              </div>
            </div>
          </Card>

          <Card>
            <Tabs tabs={[
              { label: 'Italiano', content: tabIT },
              { label: 'Inglese (Opzionale)', content: tabEN }
            ]} />
          </Card>

          <div className="admin-form-actions">
            <Button type="submit" disabled={saving} icon={<Save size={16} />}>
              {saving ? 'Salvataggio...' : 'Salva Modifiche Testuali'}
            </Button>
          </div>
        </form>
      )}
    </div>
  );
}
