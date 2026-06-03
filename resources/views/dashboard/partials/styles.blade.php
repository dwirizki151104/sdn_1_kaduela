<style>
    .role-dashboard {
        min-height: calc(100vh - 70px);
        background: #f7faf8;
        color: #111827;
        padding: 42px 16px 70px;
    }

    .role-dashboard-wrap {
        width: min(100%, 980px);
        margin: 0 auto;
    }

    .role-dashboard-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .role-dashboard-title {
        color: #0f5a45;
        font-size: clamp(1.45rem, 3vw, 2rem);
        font-weight: 900;
    }

    .role-dashboard-subtitle {
        margin-top: 4px;
        color: #64748b;
        font-size: 0.86rem;
    }

    .role-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .role-dashboard-card,
    .role-dashboard-panel {
        border: 1px solid #d8e7e2;
        border-radius: 8px;
        background: #ffffff;
        padding: 18px;
    }

    .role-dashboard-card span {
        display: block;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .role-dashboard-card strong {
        display: block;
        margin-top: 8px;
        color: #111827;
        font-size: 2rem;
        line-height: 1;
    }

    .role-dashboard-panel {
        margin-top: 16px;
    }

    .role-dashboard-panel h2 {
        color: #0f5a45;
        font-size: 1rem;
        font-weight: 900;
    }

    .role-dashboard-panel p,
    .role-dashboard-panel li {
        color: #475569;
        font-size: 0.86rem;
    }

    .role-dashboard-list {
        display: grid;
        gap: 8px;
        margin-top: 12px;
        padding: 0;
        list-style: none;
    }

    .role-dashboard-list li {
        border-bottom: 1px solid #edf3f0;
        padding-bottom: 8px;
    }

    .logout-btn {
        border: 0;
        border-radius: 8px;
        background: #0f5a45;
        color: #fff;
        padding: 10px 15px;
        font-weight: 800;
        cursor: pointer;
    }

    @media (max-width: 760px) {
        .role-dashboard-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .role-dashboard-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
