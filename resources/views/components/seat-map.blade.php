{{--
    Komponen seat-map (read-only).
    Props:
      $kapasitas   : int   - total kursi di ruangan
      $dipilih     : array - nomor kursi milik booking ini (ditampilkan biru)
      $terpakai    : array - nomor kursi milik booking lain (ditampilkan abu tua)
      $seatsPerRow : int   - kursi per baris (default 8)
--}}
@php
    $seatsPerRow = $seatsPerRow ?? 8;
    $dipilihSet  = collect($dipilih ?? [])->map(fn($n) => (int)$n)->flip()->all();
    $terpakaiSet = collect($terpakai ?? [])->map(fn($n) => (int)$n)->flip()->all();
    $rows        = (int) ceil($kapasitas / $seatsPerRow);
@endphp

<div class="seat-map-ro">

    {{-- Papan tulis --}}
    <div class="sm-board">
        <span class="sm-board-icon">▬</span> Papan Tulis / Depan Kelas
    </div>

    <div class="sm-grid">
        @for($r = 0; $r < $rows; $r++)
        <div class="sm-row">
            <span class="sm-row-lbl">{{ chr(65 + $r) }}</span>

            @for($c = 1; $c <= $seatsPerRow; $c++)
                @php $no = $r * $seatsPerRow + $c; @endphp
                @if($no > $kapasitas) @break @endif

                {{-- lorong di tengah --}}
                @if($c === 5)<div class="sm-aisle"></div>@endif

                @if(isset($dipilihSet[$no]))
                    <div class="sm-seat sm-seat--mine" title="Kursi {{ chr(65 + $r) }}{{ $c }}">{{ $c }}</div>
                @elseif(isset($terpakaiSet[$no]))
                    <div class="sm-seat sm-seat--taken" title="Kursi {{ chr(65 + $r) }}{{ $c }} - sudah terisi">{{ $c }}</div>
                @else
                    <div class="sm-seat sm-seat--free" title="Kursi {{ chr(65 + $r) }}{{ $c }} - kosong">{{ $c }}</div>
                @endif
            @endfor
        </div>
        @endfor
    </div>

    {{-- Legenda --}}
    <div class="sm-legend">
        <span class="sm-legend-item">
            <span class="sm-dot sm-dot--free"></span>Kosong
        </span>
        <span class="sm-legend-item">
            <span class="sm-dot sm-dot--mine"></span>Booking ini
        </span>
        <span class="sm-legend-item">
            <span class="sm-dot sm-dot--taken"></span>Terisi lain
        </span>
    </div>
</div>

<style>
.seat-map-ro {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 20px 16px 14px;
    overflow-x: auto;
}

.sm-board {
    background: #e8f5e9;
    border: 2px solid #a5d6a7;
    border-radius: 6px;
    text-align: center;
    padding: 6px 16px;
    font-size: .78rem;
    font-weight: 600;
    color: #2e7d32;
    width: 55%;
    margin: 0 auto 18px;
    letter-spacing: .04em;
}
.sm-board-icon { font-size: .6rem; margin-right: 4px; }

.sm-grid {
    display: flex;
    flex-direction: column;
    gap: 7px;
    width: fit-content;
    margin: 0 auto;
}

.sm-row {
    display: flex;
    align-items: center;
    gap: 5px;
}

.sm-row-lbl {
    font-size: .7rem;
    font-weight: 700;
    color: #6c757d;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.sm-aisle { width: 14px; flex-shrink: 0; }

.sm-seat {
    width: 34px;
    height: 30px;
    border-radius: 5px 5px 3px 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .65rem;
    font-weight: 700;
    border: 2px solid transparent;
    position: relative;
    flex-shrink: 0;
}
.sm-seat::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 4px; right: 4px;
    height: 4px;
    border-radius: 0 0 3px 3px;
    background: inherit;
    filter: brightness(.75);
}

.sm-seat--free  { background: #e9ecef; border-color: #ced4da; color: #adb5bd; }
.sm-seat--mine  { background: #1e3a5f; border-color: #1e3a5f; color: #fff; box-shadow: 0 0 0 2px #90aecb; }
.sm-seat--taken { background: #f5c2c7; border-color: #f1aeb5; color: #842029; }

.sm-legend {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.sm-legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .78rem;
    color: #6c757d;
}

.sm-dot {
    width: 18px;
    height: 14px;
    border-radius: 3px 3px 1px 1px;
    border: 2px solid transparent;
}
.sm-dot--free  { background: #e9ecef; border-color: #ced4da; }
.sm-dot--mine  { background: #1e3a5f; border-color: #1e3a5f; }
.sm-dot--taken { background: #f5c2c7; border-color: #f1aeb5; }
</style>
