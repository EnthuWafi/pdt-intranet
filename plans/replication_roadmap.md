# Portal Intranet PDT Manjung - Filament Replication Roadmap

This plan outlines the strategy to bridge our Filament project layout and appearance closer to `@mockup.html`, handling internal/external SSO via native Filament Panels and overriding Filament's default theme styling cleanly.

## Phase 1: Theme & Layout Customization (Styling Overrides)
- **Objective**: Match the clean-premium typography, font sizes, colors, and border radius of the mockup.
- **Actions**:
  - Enhance [`resources/css/filament/admin/theme.css`](resources/css/filament/admin/theme.css) to override Tailwind classes used by Filament (e.g., custom font-family Inter, clean card shadows, compact tables, and custom primary badge colors).
  - Customize the brand logo/header via [`resources/views/filament/components/brand.blade.php`](resources/views/filament/components/brand.blade.php).
  - Tweak top banner and sidebar footers using custom views already placed under [`resources/views/filament/hooks/`](resources/views/filament/hooks/).

## Phase 2: Core Dashboard & Modules Alignment
- **Objective**: Ensure the pages (Action Inbox, Notifications, My Activity, Chat Room, etc.) mirror the mockup's data representation and pagination blocks (e.g., 4 records per page style where applicable).
- **Actions**:
  - Review custom Livewire components and Filament pages to ensure matching table layouts.
  - Integrate specific widgets and summary cards (e.g., count-up statistics).

## Phase 3: SSO & Panel Separation Strategy
- **Objective**: Implement internal vs. external module connection.
- **Actions**:
  - **Internal Modules**: Mount them directly onto dedicated Filament Panels or pages with role-based visibility.
  - **External Modules**: Route via secure auth bridge or custom iframe/external panels as discussed, isolating session markers into `sessionStorage` to prevent `localStorage` quota issues.

## Phase 4: Daily Execution Plan & Task Breakdown
- **Day 1**: Global Theme & Typography Customization (CSS Tweaks, Sidebar/Top Bar Adjustments).
- **Day 2**: Dashboard & Summary Cards UI Refinement (KPI count-up, clean-premium cards).
- **Day 3**: Module Tables & Pagination Alignment (Compact pagination, action items).
- **Day 4**: SSO Bridge / Panel Routing & Verification.
