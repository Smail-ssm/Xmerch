const pptxgen = require("pptxgenjs");

const pptx = new pptxgen();

// ─── Theme colors ───
const C = {
  dark:     "1A1A2E",
  primary:  "6366F1",
  accent:   "10B981",
  orange:   "F59E0B",
  red:      "EF4444",
  white:    "FFFFFF",
  light:    "F1F5F9",
  gray:     "64748B",
  darkGray: "334155",
  gradient1:"667EEA",
  gradient2:"764BA2",
};

// ─── Presentation metadata ───
pptx.author  = "We-Brand.shop";
pptx.company = "We-Brand.shop";
pptx.subject = "Réunion Atelier d'Impression & Couture";
pptx.title   = "We-Brand.shop — Pitch Atelier POD";
pptx.layout  = "LAYOUT_WIDE"; // 13.33 x 7.5
  
// ─── Helper: add a styled slide ───
function addSlide(opts = {}) {
  const slide = pptx.addSlide();
  slide.background = { fill: opts.bg || C.dark };
  // Bottom bar accent
  slide.addShape(pptx.shapes.RECTANGLE, {
    x: 0, y: 6.9, w: 13.33, h: 0.6,
    fill: { color: C.primary },
  });
  slide.addText("We-Brand.shop", {
    x: 0.5, y: 7.0, w: 4, h: 0.4,
    fontSize: 10, color: C.white, fontFace: "Arial",
  });
  slide.addText("Février 2026", {
    x: 9.5, y: 7.0, w: 3, h: 0.4,
    fontSize: 10, color: C.white, fontFace: "Arial", align: "right",
  });
  return slide;
}

function addTitle(slide, title, subtitle) {
  slide.addText(title, {
    x: 0.8, y: 0.4, w: 11.7, h: 0.8,
    fontSize: 32, bold: true, color: C.white, fontFace: "Arial",
  });
  if (subtitle) {
    slide.addText(subtitle, {
      x: 0.8, y: 1.2, w: 11.7, h: 0.5,
      fontSize: 16, color: C.gray, fontFace: "Arial",
    });
  }
}

function addBullets(slide, items, opts = {}) {
  const startY = opts.y || 2.0;
  const x = opts.x || 1.0;
  const w = opts.w || 11.0;
  const fontSize = opts.fontSize || 16;
  
  const textItems = items.map(item => ({
    text: item,
    options: {
      bullet: { code: "2022" },
      fontSize: fontSize,
      color: opts.color || C.light,
      fontFace: "Arial",
      paraSpaceAfter: 8,
    }
  }));
  
  slide.addText(textItems, {
    x: x, y: startY, w: w, h: opts.h || 4.5,
    valign: "top",
  });
}

function addTable(slide, rows, opts = {}) {
  const styledRows = rows.map((row, i) => {
    return row.map(cell => ({
      text: cell,
      options: {
        fontSize: opts.fontSize || 13,
        color: i === 0 ? C.white : C.light,
        bold: i === 0,
        fill: { color: i === 0 ? C.primary : (i % 2 === 0 ? "2A2A3E" : C.dark) },
        fontFace: "Arial",
        border: { pt: 0.5, color: "3A3A5E" },
        valign: "middle",
        paraSpaceAfter: 2,
        paraSpaceBefore: 2,
      }
    }));
  });
  
  slide.addTable(styledRows, {
    x: opts.x || 0.8,
    y: opts.y || 2.2,
    w: opts.w || 11.7,
    colW: opts.colW,
    rowH: opts.rowH || 0.45,
    border: { pt: 0, color: "3A3A5E" },
  });
}

// ══════════════════════════════════════════════════════
// SLIDE 1 — Title
// ══════════════════════════════════════════════════════
let slide = addSlide();
slide.addShape(pptx.shapes.RECTANGLE, {
  x: 0, y: 0, w: 13.33, h: 7.5,
  fill: { type: "solid", color: C.dark },
});
slide.addShape(pptx.shapes.RECTANGLE, {
  x: 0, y: 2.5, w: 13.33, h: 3.0,
  fill: { color: C.primary }, transparency: 85,
});
slide.addText("We-Brand.shop", {
  x: 1, y: 1.5, w: 11, h: 1.2,
  fontSize: 52, bold: true, color: C.white, fontFace: "Arial", align: "center",
});
slide.addText("Réunion Atelier d'Impression & Couture", {
  x: 1, y: 2.8, w: 11, h: 0.8,
  fontSize: 24, color: C.light, fontFace: "Arial", align: "center",
});
slide.addText("Plateforme Print-on-Demand — Proposition de Partenariat", {
  x: 1, y: 3.6, w: 11, h: 0.6,
  fontSize: 18, color: C.gray, fontFace: "Arial", align: "center",
});
slide.addText("12 Février 2026", {
  x: 1, y: 5.0, w: 11, h: 0.6,
  fontSize: 16, color: C.accent, fontFace: "Arial", align: "center", bold: true,
});

// ══════════════════════════════════════════════════════
// SLIDE 2 — Agenda
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "📋 Agenda de la Réunion");
const agendaItems = [
  "1.  Qui sommes-nous — We-Brand.shop",
  "2.  Le concept Print-on-Demand",
  "3.  Notre plateforme — Démo live",
  "4.  Ce qu'on vous propose",
  "5.  Stratégie de prix & livraison",
  "6.  Politique de retour",
  "7.  Questions & Discussion",
  "8.  Prochaines étapes",
];
addBullets(slide, agendaItems, { fontSize: 20 });

// ══════════════════════════════════════════════════════
// SLIDE 3 — Who We Are
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🚀 Qui sommes-nous", "We-Brand.shop — Marketplace Print-on-Demand");
addBullets(slide, [
  "Plateforme e-commerce multi-vendeurs basée en Tunisie",
  "Des designers créent → Les clients achètent → L'atelier produit",
  "Zéro stock, zéro gaspillage — on ne produit que ce qui est commandé",
  "Tout le digital est prêt : site, paiement, suivi, notifications",
  "La seule pièce manquante : un partenaire de production fiable",
], { y: 2.0, fontSize: 18 });

// ══════════════════════════════════════════════════════
// SLIDE 4 — Le Marché
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "📈 Le Marché Print-on-Demand");

slide.addText("$8.5 Milliards", {
  x: 1, y: 2.2, w: 5, h: 1.2,
  fontSize: 44, bold: true, color: C.accent, fontFace: "Arial",
});
slide.addText("Marché mondial POD en 2025", {
  x: 1, y: 3.3, w: 5, h: 0.5,
  fontSize: 14, color: C.gray, fontFace: "Arial",
});
slide.addText("+26%", {
  x: 7, y: 2.2, w: 5, h: 1.2,
  fontSize: 44, bold: true, color: C.orange, fontFace: "Arial",
});
slide.addText("Croissance annuelle", {
  x: 7, y: 3.3, w: 5, h: 0.5,
  fontSize: 14, color: C.gray, fontFace: "Arial",
});

addBullets(slide, [
  "Très peu de plateformes POD locales en Tunisie & Afrique du Nord",
  "Opportunité de être le premier acteur local majeur",
  "Demande croissante pour des produits personnalisés et uniques",
  "Coûts de production locaux très compétitifs vs. international",
], { y: 4.2, fontSize: 15 });

// ══════════════════════════════════════════════════════
// SLIDE 5 — Plateforme Prête
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "✅ Notre Plateforme est 100% Prête");
addTable(slide, [
  ["Fonctionnalité", "Statut"],
  ["Site e-commerce complet", "✅ Prêt"],
  ["Paiement (Cash on Delivery + Passerelles)", "✅ Prêt"],
  ["Tableau de bord Printer / Manufacturing", "✅ Prêt"],
  ["File d'attente d'impression (Print Queue)", "✅ Prêt"],
  ["Suivi de production en temps réel", "✅ Prêt"],
  ["Génération d'étiquettes d'expédition", "✅ Prêt"],
  ["Gestion des capacités & contrôle qualité", "✅ Prêt"],
  ["Interface mobile-friendly", "✅ Prêt"],
], { colW: [8, 3.7], y: 1.8 });

// ══════════════════════════════════════════════════════
// SLIDE 6 — Flux de production
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "⚙️ Flux de Production — Le Parcours d'une Commande");

const steps = [
  { label: "Client\nCommande", color: C.primary, x: 0.3 },
  { label: "Paiement\nValidé", color: C.gradient1, x: 2.9 },
  { label: "En\nFabrication", color: C.orange, x: 5.5 },
  { label: "Impression\n/ Couture", color: C.red, x: 8.1 },
  { label: "Contrôle\nQualité", color: C.accent, x: 10.7 },
];
steps.forEach((s, i) => {
  slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
    x: s.x, y: 2.3, w: 2.2, h: 1.6,
    fill: { color: s.color },
    rectRadius: 0.15,
  });
  slide.addText(s.label, {
    x: s.x, y: 2.5, w: 2.2, h: 1.2,
    fontSize: 14, bold: true, color: C.white, fontFace: "Arial", align: "center", valign: "middle",
  });
  if (i < steps.length - 1) {
    slide.addText("→", {
      x: s.x + 2.2, y: 2.6, w: 0.7, h: 1.0,
      fontSize: 24, color: C.gray, fontFace: "Arial", align: "center", valign: "middle",
    });
  }
});

// Shipped box
slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 4.5, y: 4.5, w: 4.3, h: 1.4,
  fill: { color: C.accent },
  rectRadius: 0.15,
});
slide.addText("📦  Emballage → Expédition → Client reçoit", {
  x: 4.5, y: 4.6, w: 4.3, h: 1.2,
  fontSize: 15, bold: true, color: C.white, fontFace: "Arial", align: "center", valign: "middle",
});

// ══════════════════════════════════════════════════════
// SLIDE 7 — Ce que l'atelier voit (Dashboard)
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🖥️ Votre Dashboard — Ce que Vous Verrez");
addBullets(slide, [
  "📊  Vue d'ensemble : commandes en attente, en cours, terminées",
  "📋  File d'attente avec détails complets (design HD, taille, couleur, quantité)",
  "🔄  Changement de statut en 1 clic : Start → Done → Shipped",
  "📥  Téléchargement des fichiers d'impression en haute résolution",
  "📦  Génération automatique d'étiquettes d'expédition",
  "📈  Statistiques de production en temps réel",
  "⚡  Actions par lot : démarrer ou terminer plusieurs commandes à la fois",
], { y: 1.8, fontSize: 17 });
slide.addText("→ Démo Live sur le laptop", {
  x: 3, y: 6.0, w: 7, h: 0.6,
  fontSize: 20, bold: true, color: C.accent, fontFace: "Arial", align: "center",
});

// ══════════════════════════════════════════════════════
// SLIDE 8 — Ce qu'on vous propose
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🤝 Ce qu'on Vous Propose");

// Left column - What we bring
slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 0.5, y: 1.8, w: 5.8, h: 4.8,
  fill: { color: "1E1E36" },
  rectRadius: 0.15,
});
slide.addText("💻 Nous (We-Brand)", {
  x: 0.7, y: 1.9, w: 5.4, h: 0.6,
  fontSize: 18, bold: true, color: C.primary, fontFace: "Arial",
});
addBullets(slide, [
  "Volume régulier de commandes",
  "Fichiers design HD prêts à imprimer",
  "Paiement garanti (commandes pré-payées)",
  "Support technique & formation dashboard",
  "Marketing et acquisition clients",
], { x: 0.7, y: 2.5, w: 5.4, h: 4.0, fontSize: 15 });

// Right column - What they bring
slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 6.8, y: 1.8, w: 5.8, h: 4.8,
  fill: { color: "1E1E36" },
  rectRadius: 0.15,
});
slide.addText("🏭 Vous (L'Atelier)", {
  x: 7.0, y: 1.9, w: 5.4, h: 0.6,
  fontSize: 18, bold: true, color: C.accent, fontFace: "Arial",
});
addBullets(slide, [
  "Production fiable, qualité constante",
  "Délais respectés (2-3 jours max)",
  "Communication transparente",
  "Capacité claire par jour/semaine",
  "Flexibilité tailles, couleurs, supports",
], { x: 7.0, y: 2.5, w: 5.4, h: 4.0, fontSize: 15 });

// ══════════════════════════════════════════════════════
// SLIDE 9 — Pricing Strategy
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "💰 Stratégie de Prix — Décomposition T-Shirt");
addTable(slide, [
  ["Composant", "Montant", "% du prix"],
  ["Vêtement vierge (coton)", "8 TND", "20%"],
  ["Impression DTG (A4)", "7 TND", "17.5%"],
  ["Total Atelier", "15 TND", "37.5%"],
  ["Marge Designer", "5 TND", "12.5%"],
  ["Commission Plateforme (fixe + %)", "6 TND", "15%"],
  ["Livraison (Grand Tunis)", "7 TND", "17.5%"],
  ["Emballage", "2 TND", "5%"],
  ["PRIX CLIENT FINAL", "35 TND", "100%"],
], { colW: [6, 3, 2.7], y: 1.8, fontSize: 14 });

// ══════════════════════════════════════════════════════
// SLIDE 10 — Multiple product pricing
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "📊 Simulations de Prix — Tous Produits");
addTable(slide, [
  ["Produit", "Coût Atelier", "Prix Vente", "Marge Totale"],
  ["T-Shirt DTG (A4)", "15 TND", "35 TND", "13 TND"],
  ["T-Shirt DTG (A3)", "18 TND", "42 TND", "15 TND"],
  ["Hoodie / Sweat", "37 TND", "69 TND", "21 TND"],
  ["Mug Sublimation", "8 TND", "30 TND", "9 TND"],
  ["Tote Bag", "10 TND", "25 TND", "8 TND"],
  ["Casquette brodée", "15 TND", "35 TND", "12 TND"],
], { colW: [4, 2.5, 2.5, 2.7], y: 1.8 });

slide.addText("* Marge totale = commission plateforme + marge designer (hors livraison)", {
  x: 1, y: 5.5, w: 11, h: 0.4,
  fontSize: 11, italic: true, color: C.gray, fontFace: "Arial",
});

// ══════════════════════════════════════════════════════
// SLIDE 11 — Delivery Zones
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🚚 Stratégie de Livraison — Zones & Tarifs");
addTable(slide, [
  ["Zone", "Villes", "Délai", "Prix"],
  ["Zone 1 — Grand Tunis", "Tunis, Ariana, Ben Arous, Manouba", "1-2 j", "7 TND"],
  ["Zone 2 — Nord & Cap Bon", "Bizerte, Nabeul, Hammamet", "2-3 j", "9 TND"],
  ["Zone 3 — Centre", "Sousse, Monastir, Sfax, Kairouan", "2-3 j", "9 TND"],
  ["Zone 4 — Sud", "Gabès, Médenine, Gafsa, Tozeur", "3-5 j", "12 TND"],
  ["Zone 5 — International", "Algérie, Libye, Europe", "7-15 j", "25-45 TND"],
], { colW: [3, 4.5, 1.5, 2.7], y: 1.8 });

slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 2.5, y: 5.5, w: 8, h: 1.0,
  fill: { color: C.accent }, transparency: 20,
  rectRadius: 0.1,
});
slide.addText("💡 Livraison GRATUITE au-dessus de 60 TND — pousse le panier moyen ↑", {
  x: 2.5, y: 5.5, w: 8, h: 1.0,
  fontSize: 16, bold: true, color: C.accent, fontFace: "Arial", align: "center", valign: "middle",
});

// ══════════════════════════════════════════════════════
// SLIDE 12 — Revenue Split
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "💵 Qui Gagne Quoi — Répartition des Revenus");

const actors = [
  { label: "🏭 Atelier\n15 TND (35.7%)", color: C.orange, x: 0.5, w: 2.8 },
  { label: "🎨 Designer\n5 TND (11.9%)", color: C.gradient2, x: 3.6, w: 2.8 },
  { label: "💻 We-Brand\n6 TND (14.3%)", color: C.primary, x: 6.7, w: 2.8 },
  { label: "🚚 Livraison\n7 TND (16.7%)", color: C.accent, x: 9.8, w: 2.8 },
];
actors.forEach(a => {
  slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
    x: a.x, y: 2.0, w: a.w, h: 2.0,
    fill: { color: a.color },
    rectRadius: 0.15,
  });
  slide.addText(a.label, {
    x: a.x, y: 2.2, w: a.w, h: 1.6,
    fontSize: 16, bold: true, color: C.white, fontFace: "Arial", align: "center", valign: "middle",
  });
});

// Projection table
addTable(slide, [
  ["Volume", "Atelier/mois", "Designers/mois", "We-Brand/mois"],
  ["100 cmd/mois", "1,500 TND", "500 TND", "600 TND"],
  ["300 cmd/mois", "4,500 TND", "1,500 TND", "1,800 TND"],
  ["500 cmd/mois", "7,500 TND", "2,500 TND", "3,000 TND"],
], { y: 4.6, colW: [3, 3, 2.8, 2.9], fontSize: 14 });

// ══════════════════════════════════════════════════════
// SLIDE 13 — Return Policy
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "📜 Politique de Retour — Simple & Claire");

slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 0.5, y: 1.8, w: 5.8, h: 4.6,
  fill: { color: "1E1E36" },
  rectRadius: 0.15,
});
slide.addText("✅ Retour / Échange Accepté", {
  x: 0.7, y: 1.9, w: 5.4, h: 0.6,
  fontSize: 18, bold: true, color: C.accent, fontFace: "Arial",
});
addBullets(slide, [
  "Défaut d'impression visible",
  "Taille envoyée incorrecte",
  "Produit endommagé à la réception",
  "Délai : sous 7 jours après réception",
], { x: 0.7, y: 2.5, w: 5.4, h: 3.5, fontSize: 15 });

slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 6.8, y: 1.8, w: 5.8, h: 4.6,
  fill: { color: "1E1E36" },
  rectRadius: 0.15,
});
slide.addText("❌ Retour Refusé", {
  x: 7.0, y: 1.9, w: 5.4, h: 0.6,
  fontSize: 18, bold: true, color: C.red, fontFace: "Arial",
});
addBullets(slide, [
  "Changement d'avis (produit personnalisé)",
  "Variation légère de couleur (écran ≠ print)",
  "Le client a commandé la mauvaise taille",
  "→ Taux de retour prévu : 1-2% max",
], { x: 7.0, y: 2.5, w: 5.4, h: 3.5, fontSize: 15 });

// ══════════════════════════════════════════════════════
// SLIDE 14 — Volume Discounts
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "📉 Dégressivité Volume — Gagnant-Gagnant");
addTable(slide, [
  ["Volume Mensuel", "Prix Atelier / Pièce", "Remise", "Vos Revenus"],
  ["1-50 pièces", "15 TND", "Prix standard", "750 TND"],
  ["51-150 pièces", "13.5 TND", "-10%", "2,025 TND"],
  ["151-300 pièces", "12.75 TND", "-15%", "3,825 TND"],
  ["300+ pièces", "12 TND", "-20%", "3,600+ TND"],
], { colW: [3, 3, 2.5, 3.2], y: 2.0 });

slide.addText("💡 Plus le volume augmente, plus vous gagnez en valeur absolue\nmême avec un prix unitaire réduit.", {
  x: 1.5, y: 5.0, w: 10, h: 1.0,
  fontSize: 16, italic: true, color: C.orange, fontFace: "Arial", align: "center",
});

// ══════════════════════════════════════════════════════
// SLIDE 15 — Payment Model
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🏦 Comment on Vous Paie");

const payOpts = [
  { title: "Option A — À la Commande", desc: "Paiement dès qu'un lot est validé\n(Recommandé au démarrage)", color: C.accent, x: 0.5 },
  { title: "Option B — Hebdomadaire", desc: "Cumul de la semaine\nPaiement chaque lundi", color: C.primary, x: 4.5 },
  { title: "Option C — Mensuel", desc: "Facturation mensuelle\n(Quand le volume grandit)", color: C.orange, x: 8.5 },
];
payOpts.forEach(p => {
  slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
    x: p.x, y: 2.0, w: 3.8, h: 3.0,
    fill: { color: "1E1E36" },
    rectRadius: 0.15,
    line: { color: p.color, width: 2 },
  });
  slide.addText(p.title, {
    x: p.x + 0.2, y: 2.2, w: 3.4, h: 0.7,
    fontSize: 16, bold: true, color: p.color, fontFace: "Arial", align: "center",
  });
  slide.addText(p.desc, {
    x: p.x + 0.2, y: 3.0, w: 3.4, h: 1.6,
    fontSize: 13, color: C.light, fontFace: "Arial", align: "center", valign: "middle",
  });
});

slide.addShape(pptx.shapes.ROUNDED_RECTANGLE, {
  x: 2, y: 5.5, w: 9, h: 1.0,
  fill: { color: C.accent }, transparency: 85,
  rectRadius: 0.1,
});
slide.addText("🔒 Garantie : On ne lance en production que les commandes DÉJÀ PAYÉES par le client", {
  x: 2, y: 5.5, w: 9, h: 1.0,
  fontSize: 15, bold: true, color: C.accent, fontFace: "Arial", align: "center", valign: "middle",
});

// ══════════════════════════════════════════════════════
// SLIDE 16 — Why Partner With Us
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "💎 Pourquoi Travailler avec Nous");
addBullets(slide, [
  "✅  Zéro risque financier — vous ne produisez que ce qui est déjà payé",
  "✅  Volume croissant — notre objectif : 500+ commandes/mois d'ici 6 mois",
  "✅  Dashboard dédié — gestion de production simplifiée et professionnelle",
  "✅  Pas de stock à gérer — production à la demande uniquement",
  "✅  Marketing à notre charge — vous vous concentrez sur la production",
  "✅  Paiement flexible et garanti — à la commande, hebdo, ou mensuel",
  "✅  Possibilité de badge « Fabriqué par [Votre Atelier] » sur le site",
], { y: 1.8, fontSize: 18 });

// ══════════════════════════════════════════════════════
// SLIDE 17 — Next Steps
// ══════════════════════════════════════════════════════
slide = addSlide();
addTitle(slide, "🚀 Prochaines Étapes");
addTable(slide, [
  ["#", "Action", "Responsable", "Délai"],
  ["1", "Envoyer 3 designs de test en HD", "Nous", "Ce soir"],
  ["2", "Produire 3 échantillons test", "Atelier", "3-5 jours"],
  ["3", "Valider qualité + grille tarifaire", "Ensemble", "Même semaine"],
  ["4", "Créer compte atelier + formation", "Nous", "1 jour"],
  ["5", "Phase pilote — 10-20 commandes", "Ensemble", "2 semaines"],
  ["6", "Debrief et ajustements", "Ensemble", "Fin février"],
  ["7", "Go Live public 🚀", "Tous", "Mars 2026"],
], { colW: [0.7, 5, 2.5, 3.5], y: 1.8 });

// ══════════════════════════════════════════════════════
// SLIDE 18 — Closing
// ══════════════════════════════════════════════════════
slide = addSlide();
slide.addShape(pptx.shapes.RECTANGLE, {
  x: 0, y: 2.0, w: 13.33, h: 3.5,
  fill: { color: C.primary }, transparency: 85,
});
slide.addText("Merci pour votre temps !", {
  x: 1, y: 2.0, w: 11, h: 1.2,
  fontSize: 42, bold: true, color: C.white, fontFace: "Arial", align: "center",
});
slide.addText("On peut commencer avec 3 échantillons.\nAucun engagement. Juste un test.", {
  x: 1, y: 3.2, w: 11, h: 1.2,
  fontSize: 22, color: C.light, fontFace: "Arial", align: "center",
});
slide.addText("→ On fait un essai ensemble cette semaine ?", {
  x: 1, y: 4.5, w: 11, h: 0.8,
  fontSize: 24, bold: true, color: C.accent, fontFace: "Arial", align: "center",
});
slide.addText("We-Brand.shop  •  contact@we-brand.shop", {
  x: 1, y: 5.8, w: 11, h: 0.5,
  fontSize: 14, color: C.gray, fontFace: "Arial", align: "center",
});

// ─── Generate ───
const outPath = "ATELIER_MEETING_PITCH.pptx";
pptx.writeFile({ fileName: outPath })
  .then(() => console.log(`✅ Presentation saved to: ${outPath}`))
  .catch(err => console.error("Error:", err));
