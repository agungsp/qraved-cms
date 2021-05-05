@forelse ($users as $user)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2">
            <div class="row">
                <div class="col-auto pl-4">
                    <img class="img-thumbnail rounded-circle shadow-sm" width="50"
                        src="https://ui-avatars.com/api/?name={{ Str::slug($user->name) }}"
                        alt="Profile Picture">
                </div>
                <div class="col pt-2">
                    {{ $user->name }}
                    @if (auth()->id() == $user->id)
                        (You)
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse
