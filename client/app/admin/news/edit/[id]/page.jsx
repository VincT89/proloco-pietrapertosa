"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useState, useEffect, use } from 'react';
import { useRouter } from 'next/navigation';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { ArrowLeft, Save } from 'lucide-react';
import 'react-quill-new/dist/quill.snow.css';

import Card from '../../../components/ui/Card';
import Input from '../../../components/ui/Input';
import Textarea from '../../../components/ui/Textarea';
import Button from '../../../components/ui/Button';
import Tabs from '../../../components/ui/Tabs';
import Select from '../../../components/ui/Select';

const ReactQuill = dynamic(() => import('react-quill-new'), { ssr: false });

export default function NewsEditor({ params }) {
  const router = useRouter();
  const unwrappedParams = use(params);
  const { id } = unwrappedParams;
  const isNew = id === 'new';

  const [loading, setLoading] = useState(!isNew);
  const [saving, setSaving] = useState(false);
  const [mediaList, setMediaList] = useState([]);
  
  const [formData, setFormData] = useState({
    title: '', title_en: '',
    excerpt: '', excerpt_en: '',
    content: '', content_en: '',
    status: 'draft',
    cover_media_id: '',
    gallery: []
  });

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

    if (!isNew) {
      const fetchNewsItem = async () => {
        try {
          // Passiamo lang senza valore per prendere tutti i dati dal backend e non fare fallback automatico
          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news/${id}`);
          if (res.ok) {
            const data = await res.json();
            setFormData({
              title: data.news.title || '',
              title_en: data.news.title_en || '',
              excerpt: data.news.excerpt || '',
              excerpt_en: data.news.excerpt_en || '',
              content: data.news.content || '',
              content_en: data.news.content_en || '',
              status: data.news.status || 'draft',
              cover_media_id: data.news.cover_media_id || '',
              gallery: typeof data.news.gallery === 'string' ? safeJsonParse(data.news.gallery) : (data.news.gallery || [])
            });
          }
        } catch (err) {
          console.error(err);
        } finally {
          setLoading(false);
        }
      };
      fetchNewsItem();
    }
  }, [id, isNew]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const toggleGallery = (url) => {
    setFormData(prev => {
      const g = prev.gallery || [];
      if (g.includes(url)) {
        return { ...prev, gallery: g.filter(u => u !== url) };
      } else {
        return { ...prev, gallery: [...g, url] };
      }
    });
  };

  const handleEditorChange = (content) => setFormData(prev => ({ ...prev, content }));
  const handleEditorChangeEn = (content_en) => setFormData(prev => ({ ...prev, content_en }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);

    try {
      const token = localStorage.getItem('admin_token');
      const url = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/news${isNew ? '' : `/${id}`}`;
      const method = isNew ? 'POST' : 'PUT';

      const payload = { ...formData };
      if (!payload.cover_media_id) payload.cover_media_id = null;

      const res = await fetch(url, {
        method,
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` 
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) throw new Error('Errore durante il salvataggio');
      
      router.push('/admin/news');
    } catch (err) {
      alert(err.message);
      setSaving(false);
    }
  };

  const modules = {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike', 'blockquote'],
      [{'list': 'ordered'}, {'list': 'bullet'}],
      ['link', 'image', 'video'],
      ['clean']
    ],
  };

  if (loading) return <div style={{ color: 'var(--admin-muted)' }}>Caricamento in corso...</div>;

  const tabIT = (
    <div className="flex-col">
      <Input label="Titolo (IT)" name="title" value={formData.title} onChange={handleChange} required />
      <Textarea label="Estratto (IT)" name="excerpt" value={formData.excerpt} onChange={handleChange} />
      <div>
        <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Contenuto Completo (IT)</label>
        <div style={{ background: 'white', borderRadius: '4px', border: '1px solid var(--admin-border)', overflow: 'hidden' }}>
          <ReactQuill theme="snow" value={formData.content} onChange={handleEditorChange} modules={modules} style={{ height: '400px' }} />
        </div>
      </div>
    </div>
  );

  const tabEN = (
    <div className="flex-col">
      <Input label="Titolo (EN)" name="title_en" value={formData.title_en} onChange={handleChange} />
      <Textarea label="Estratto (EN)" name="excerpt_en" value={formData.excerpt_en} onChange={handleChange} />
      <div>
        <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Contenuto Completo (EN)</label>
        <div style={{ background: 'white', borderRadius: '4px', border: '1px solid var(--admin-border)', overflow: 'hidden' }}>
          <ReactQuill theme="snow" value={formData.content_en} onChange={handleEditorChangeEn} modules={modules} style={{ height: '400px' }} />
        </div>
      </div>
    </div>
  );

  return (
    <div style={{ maxWidth: '1000px' }}>
      <div className="admin-page-header">
        <div className="flex-row" style={{ gap: '16px' }}>
          <Link href="/admin/news" style={{ color: 'var(--admin-muted)' }}>
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Nuova Notizia' : 'Modifica Notizia'}</h1>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="flex-col">
        <Card>
          <div className="grid-2">
            <Select 
              label="Stato Pubblicazione" 
              name="status" 
              value={formData.status} 
              onChange={handleChange}
              options={[
                { value: "draft", label: "Bozza (Nascosta)" },
                { value: "published", label: "Pubblicata (Visibile)" }
              ]}
            />
            <Select 
              label="Immagine di Copertina" 
              name="cover_media_id" 
              value={formData.cover_media_id} 
              onChange={handleChange}
              options={[
                { value: "", label: "-- Nessuna Copertina --" },
                ...mediaList.map(m => ({ value: m.id, label: `${m.folder}/${m.public_id.split('/').pop()}` }))
              ]}
            />
          </div>
        </Card>

        <Card>
          <Tabs tabs={[
            { label: 'Italiano', content: tabIT },
            { label: 'Inglese (Opzionale)', content: tabEN }
          ]} />
        </Card>

        <Card>
          <div className="flex-col">
            <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Galleria Immagini</label>
            <div className="admin-gallery-grid">
              {mediaList.length === 0 ? <span style={{color: 'var(--admin-muted)'}}>Nessun media.</span> : mediaList.map(media => {
                const isSelected = (formData.gallery || []).includes(media.url);
                return (
                  <div 
                    key={media.id} 
                    onClick={() => toggleGallery(media.url)}
                    style={{ 
                      cursor: 'pointer', position: 'relative', aspectRatio: '1', borderRadius: '4px', overflow: 'hidden',
                      border: isSelected ? '3px solid var(--admin-accent)' : '1px solid var(--admin-border)'
                    }}
                  >
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    {media.resource_type === 'video' ? (
                      <video src={media.url} style={{ width: '100%', height: '100%', objectFit: 'cover', opacity: isSelected ? 1 : 0.6 }} />
                    ) : (
                      <img src={media.url} alt="Media" style={{ width: '100%', height: '100%', objectFit: 'cover', opacity: isSelected ? 1 : 0.6 }} />
                    )}
                    {isSelected && <div style={{ position: 'absolute', top: 5, right: 5, background: 'var(--admin-accent)', color: 'white', borderRadius: '50%', width: 20, height: 20, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.8rem', fontWeight: 'bold' }}>✓</div>}
                  </div>
                );
              })}
            </div>
          </div>
        </Card>

        <div style={{ display: 'flex', justifyContent: 'flex-end', marginTop: '16px' }}>
          <Button type="submit" disabled={saving} icon={<Save size={16} />}>
            {saving ? 'Salvataggio...' : 'Salva Notizia'}
          </Button>
        </div>
      </form>
    </div>
  );
}
