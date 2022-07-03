
<h3 class="text-center d-print">{{ optional(auth()->user()->company)->name }}</h3>
<h5 class="text-center d-print" style="margin-top: -10px !important;">{{ optional(auth()->user()->company)->address }}</h5>
<h5 class="text-center d-print" style="margin-top: -10px !important;">Phone: {{ optional(auth()->user()->company)->phone }}, Email: {{ optional(auth()->user()->company)->email }}</h5>