@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    مدیریت کاربران
                </h3>

                <p class="text-muted mb-0">
                    مشاهده کاربران و مدیریت نقش مدیر
                </p>

            </div>

        </div>


        {{-- Success --}}

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- Error --}}

        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                نام
                            </th>

                            <th>
                                ایمیل
                            </th>

                            <th>
                                تلفن
                            </th>

                            <th>
                                نقش
                            </th>

                            <th>
                                تاریخ عضویت
                            </th>

                            <th>
                                عملیات
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($users as $user)

                            <tr>

                                <td>
                                    {{ $user->id }}
                                </td>


                                <td>

                                    {{ $user->name }}

                                    @if($user->lastname)
                                        {{ $user->lastname }}
                                    @endif

                                </td>


                                <td>
                                    {{ $user->email }}
                                </td>


                                <td>
                                    {{ $user->tel ?? '-' }}
                                </td>


                                <td>

                                    @if($user->adminUser?->role === 'admin')

                                        <span class="badge bg-danger">
                                            مدیر
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            کاربر
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $user->created_at?->format('Y/m/d') }}

                                </td>


                                <td>

                                    @if($user->adminUser?->role === 'admin')

                                        @if((int) $user->id !== (int) auth()->id())

                                            <form
                                                action="{{ route('admin.users.remove-admin', $user) }}"
                                                method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('آیا از حذف نقش مدیر این کاربر مطمئن هستید؟')"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-warning btn-sm"
                                                >
                                                    حذف مدیر
                                                </button>

                                            </form>

                                        @else

                                            <span class="text-muted">
                                                مدیر فعلی
                                            </span>

                                        @endif

                                    @else

                                        <form
                                            action="{{ route('admin.users.make-admin', $user) }}"
                                            method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('آیا این کاربر به مدیر تبدیل شود؟')"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm"
                                            >
                                                مدیر کردن
                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    کاربری وجود ندارد.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $users->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
