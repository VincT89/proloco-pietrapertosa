"use client";
import { safeJsonParse } from '@/utils/safeJson';

import React, { useState, useEffect, Suspense, use } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft, Save } from 'lucide-react';

import Card from '@/app/admin/components/ui/Card';
import Input from '@/app/admin/components/ui/Input';
import Textarea from '@/app/admin/components/ui/Textarea';
import Button from '@/app/admin/components/ui/Button';
import Tabs from '@/app/admin/components/ui/Tabs';

function DirectoryEditContent({ id }) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const isNew = id === 'new';

  const defaultCategory = searchParams.get('category') || 'territorio_aziende';

  const [formData, setFormData] = useState({
    category: defaultCategory,
    title: '', title_en: '',
    subtitle: '', subtitle_en: '',
    description: '', description_en: '',
    contact_info: '', contact_info_en: '',
    gallery: []
  });
  
  const [mediaList, setMediaList] = useState([]);
  const [loading, setLoading] = useState(!isNew);
  const [saving, setSaving] = useState(false);

  const categories = [
    { id: 'comunita', name: 'Comunità - Associazioni' },
    { id: 'eventi_annuali', name: 'Eventi Annuali (Card)' },
    { id: 'territorio_aziende', name: 'Territorio - Aziende Agricole' },
    { id: 'territorio_foodtruck', name: 'Territorio - Food Truck' },
    { id: 'territorio_artigiani', name: 'Territorio - Artigiani' },
    { id: 'sapori_piatti', name: 'Sapori - Piatti Tipici' }
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

    if (!isNew) {
      const fetchItem = async () => {
        try {
          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory/${id}`);
          if (res.ok) {
            const data = await res.json();
            setFormData({
              category: data.item.category || 'territorio_aziende',
              title: data.item.title || '',
              title_en: data.item.title_en || '',
              subtitle: data.item.subtitle || '',
              subtitle_en: data.item.subtitle_en || '',
              description: data.item.description || '',
              description_en: data.item.description_en || '',
              contact_info: data.item.contact_info || '',
              contact_info_en: data.item.contact_info_en || '',
              gallery: typeof data.item.gallery === 'string' ? safeJsonParse(data.item.gallery) : (data.item.gallery || [])
            });
          }
        } catch (err) {
          console.error(err);
        } finally {
          setLoading(false);
        }
      };
      fetchItem();
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

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);

    try {
      const token = localStorage.getItem('admin_token');
      const url = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api'}/directory${isNew ? '' : `/${id}`}`;
      const method = isNew ? 'POST' : 'PUT';

      const res = await fetch(url, {
        method,
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}` 
        },
        body: JSON.stringify(formData)
      });

      if (!res.ok) throw new Error('Errore durante il salvataggio');
      
      router.push('/admin/directory');
    } catch (err) {
      alert(err.message);
      setSaving(false);
    }
  };

  if (loading) return <div style={{ color: 'var(--admin-muted)' }}>Caricamento in corso...</div>;

  const tabIT = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (Nome - IT)" name="title" value={formData.title} onChange={handleChange} required />
        <Input label="Sottotitolo (IT)" name="subtitle" value={formData.subtitle} onChange={handleChange} />
      </div>
      <Textarea label="Descrizione (IT)" name="description" value={formData.description} onChange={handleChange} rows={5} />
      <Input label="Info Contatti (Telefono / Email / Fonte - IT)" name="contact_info" value={formData.contact_info} onChange={handleChange} />
    </div>
  );

  const tabEN = (
    <div className="flex-col">
      <div className="grid-2">
        <Input label="Titolo (Nome - EN)" name="title_en" value={formData.title_en} onChange={handleChange} />
        <Input label="Sottotitolo (EN)" name="subtitle_en" value={formData.subtitle_en} onChange={handleChange} />
      </div>
      <Textarea label="Descrizione (EN)" name="description_en" value={formData.description_en} onChange={handleChange} rows={5} />
      <Input label="Info Contatti (EN)" name="contact_info_en" value={formData.contact_info_en} onChange={handleChange} />
    </div>
  );

  return (
    <div style={{ maxWidth: '1000px' }}>
      <div className="admin-page-header">
        <div className="flex-row" style={{ gap: '16px' }}>
          <Link href="/admin/directory" style={{ color: 'var(--admin-muted)' }}>
            <ArrowLeft size={24} />
          </Link>
          <h1 className="admin-page-title">{isNew ? 'Nuovo Elemento' : 'Modifica Elemento'}</h1>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="flex-col">
        <Card>
          <div className="flex-col">
            <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Categoria</label>
            <select 
              name="category" value={formData.category} onChange={handleChange}
              style={{ width: '100%', padding: '12px 14px', border: '1px solid var(--admin-border)', borderRadius: '4px', outline: 'none' }}
            >
              {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
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
            <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Immagini Selezionate (Clicca sulla X per rimuovere)</label>
            <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', marginBottom: '24px' }}>
              {(formData.gallery || []).map((url, i) => (
                <div 
                  key={i} 
                  style={{ position: 'relative', width: '100px', height: '100px', borderRadius: '6px', overflow: 'hidden', cursor: 'pointer', border: '2px solid var(--admin-accent)' }}
                  onClick={() => toggleGallery(url)}
                >
                  <img src={url} alt="Selected" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                  <div style={{ position: 'absolute', top: 4, right: 4, background: 'rgba(0,0,0,0.7)', color: 'white', borderRadius: '50%', width: '24px', height: '24px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '1rem', fontWeight: 'bold', transition: 'background 0.2s' }} onMouseEnter={e => e.currentTarget.style.background = 'red'} onMouseLeave={e => e.currentTarget.style.background = 'rgba(0,0,0,0.7)'}>&times;</div>
                </div>
              ))}
              {(formData.gallery || []).length === 0 && <span style={{ color: 'var(--admin-muted)', fontSize: '0.9rem', fontStyle: 'italic' }}>Nessuna immagine selezionata</span>}
            </div>

            <label style={{ display: 'block', color: 'var(--admin-muted)', marginBottom: '8px', fontSize: '0.75rem', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.05em' }}>Scegli dalla Libreria Media (Clicca per aggiungere/rimuovere)</label>
            <div className="admin-gallery-grid">
              {mediaList.length === 0 ? <span style={{color: 'var(--admin-muted)'}}>Nessun media caricato.</span> : mediaList.map(media => {
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
            {saving ? 'Salvataggio...' : 'Salva Elemento'}
          </Button>
        </div>
      </form>
    </div>
  );
}

export default function DirectoryEdit({ params }) {
  const unwrappedParams = use(params);
  return (
    <Suspense fallback={<div style={{ color: 'var(--admin-muted)' }}>Caricamento...</div>}>
      <DirectoryEditContent id={unwrappedParams.id} />
    </Suspense>
  );
}
