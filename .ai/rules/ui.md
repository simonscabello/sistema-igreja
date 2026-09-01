---
paths:
  - resources/js/components/ui/Button.tsx
  - 'resources/js/components/ui/**'
---

# Ui

## Centralize UI actions in Button helpers
All UI actions go through Button.tsx: CreateButton/SaveButton/SearchButton/FilterButton/ViewButton/EditButton/BackButton/RowActions. Table Ver/Editar use RowActions + ViewButton/EditButton. DeleteButton compact=true in tables. Do not restyle one-off teal/green buttons. Icons are inferred from labels (Salvar, Cancelar, Ver, Editar, etc.) or passed via icon.

## shadcn primitives plus Button helpers
UI actions go through Button.tsx helpers on top of shadcn button.tsx. Table Ver/Editar use RowActions + ViewButton/EditButton. Destructive actions use DeleteButton + AlertDialog, not SweetAlert.
