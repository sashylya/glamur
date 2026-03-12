@extends('layouts.admin')

@section('title', 'Модерация отзывов - Админ-панель')
@section('header', 'Модерация отзывов')

@section('content')
<div class="reviews-moderation">

    <div class="reviews-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #7c2b2b;  padding: 20px; border-radius: 12px; color: white;">
            <div style="font-size: 32px; font-weight: bold;">{{ $reviews->total() }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Всего отзывов</div>
        </div>
        <div style="background: #7c2b2b; padding: 20px; border-radius: 12px; color: white;">
            <div style="font-size: 32px; font-weight: bold;">{{ $reviews->where('is_approved', false)->count() }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Ожидают проверки</div>
        </div>
        <div style="background: #7c2b2b; padding: 20px; border-radius: 12px; color: white;">
            <div style="font-size: 32px; font-weight: bold;">{{ $reviews->where('is_approved', true)->count() }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Одобрено</div>
        </div>
    </div>

    <div class="reviews-grid">
        @forelse($reviews as $review)
            <div class="review-card" style="background: #2b2d39; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; border-left: 4px solid {{ !$review->is_approved ? '#ffc107' : '#28a745' }};">
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #ffffff;">{{ $review->user->name }}</div>
                                <div style="font-size: 12px; color: #999;">{{ $review->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; background: {{ !$review->is_approved ? '#fff3cd' : '#d4edda' }}; color: {{ !$review->is_approved ? '#856404' : '#155724' }};">
                                {{ !$review->is_approved ? 'Ожидает проверки' : 'Одобрен' }}
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <a href="{{ route('catalog.show', $review->product) }}" style="color: #ffffff; text-decoration: none; font-weight: 500;">
                            {{ $review->product->name }}
                        </a>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; gap: 5px; margin-bottom: 10px;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= $review->rating ? '#ffc107' : '#ddd' }}; font-size: 18px;">★</span>
                            @endfor
                        </div>

                        @if($review->advantages)
                            <div style="margin-bottom: 10px; background: #e8f5e9; padding: 10px; border-radius: 8px;">
                                <div style="font-size: 12px; color: #2e7d32; margin-bottom: 4px;">Достоинства</div>
                                <div style="color: #333;">{{ $review->advantages }}</div>
                            </div>
                        @endif

                        @if($review->disadvantages)
                            <div style="margin-bottom: 10px; background: #ffebee; padding: 10px; border-radius: 8px;">
                                <div style="font-size: 12px; color: #c62828; margin-bottom: 4px;">Недостатки</div>
                                <div style="color: #333;">{{ $review->disadvantages }}</div>
                            </div>
                        @endif

                        @if($review->comment)
                            <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; color: #666; line-height: 1.5;">
                                {{ $review->comment }}
                            </div>
                        @endif
                    </div>

                    @if(!$review->is_approved)
                        <div style="display: flex; gap: 10px; border-top: 1px solid #eee; padding-top: 15px;">
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="width: 100%; padding: 10px; background: #2b7c4d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <span>✓</span> Одобрить
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 100%; padding: 10px; background: #7c2b2b; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="return confirm('Удалить отзыв?')">
                                    <span>✗</span> Отклонить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 20px;">📭</div>
                <h3 style="color: #666; margin-bottom: 10px;">Нет отзывов для модерации</h3>
                <p style="color: #999;">Когда пользователи оставят отзывы, они появятся здесь</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 30px;">
        {{ $reviews->links() }}
    </div>
</div>

<style>
.review-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.pagination a, .pagination span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
}

.pagination .active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .reviews-stats {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection