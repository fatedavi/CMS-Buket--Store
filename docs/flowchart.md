graph TD
%% Global Styling
classDef user fill:#f9f,stroke:#333,stroke-width:2px;
classDef public fill:#e1f5fe,stroke:#01579b;
classDef admin fill:#fff3e0,stroke:#e65100;
classDef external fill:#c8e6c9,stroke:#2e7d32,stroke-dasharray: 5 5;

    USER([👤 Pengunjung Website]) --> ENTRY{Akses Web}

    %% FRONTEND SECTION
    subgraph Halaman_Publik [🌐 Area Pengunjung]
        ENTRY --> HOME[🏠 Beranda / Homepage]

        HOME --> SEC_H[Bagian Beranda:]
        SEC_H --- H1[Hero & Promo]
        SEC_H --- H2[Keunggulan Kami]
        SEC_H --- H3[Katalog Produk]
        SEC_H --- H4[Testimoni & Stats]

        HOME --> KATALOG[🛍️ Katalog Produk]
        KATALOG --> DETAIL[🔍 Detail Produk]

        HOME --> BLOG[📝 Artikel & Tips]
        BLOG --> B_DET[📖 Baca Artikel]

        HOME --> KONTAK[📞 Hubungi Kami]
    end

    %% ACTION SECTION
    subgraph Interaksi [💬 Jalur Komunikasi]
        DETAIL --> WA
        KONTAK --> WA
        CHAT[🗨️ Floating Chat] --> WA
        WA((✅ WhatsApp Chat))
    end

    %% ADMIN SECTION
    subgraph Panel_Admin [⚙️ Kelola Website]
        ENTRY --> ADM_LOG[🔐 Admin Login]
        ADM_LOG --> DASH[📊 Dashboard Ringkasan]
        DASH --> P1[📦 Kelola Produk]
        DASH --> P2[✍️ Tulis Artikel]
        DASH --> P3[⚙️ Pengaturan Web]
    end

    %% Footer & Error
    Halaman_Publik --- FOOT[🦶 Info Footer & Sosmed]
    ENTRY -.-> ERR[❓ Halaman 404 / Tidak Ketemu] --> HOME

    %% Assign Classes
    class USER user;
    class HOME,KATALOG,DETAIL,BLOG,B_DET,KONTAK public;
    class ADM_LOG,DASH,P1,P2,P3 admin;
    class WA external;
