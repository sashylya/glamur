@extends('layouts.app')

@section('title', $product->name . ' - Гламур')

@section('content')
<div class="details-layout">
    <!-- Галерея -->
    <div class="gallery">
        <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}" class="main-image">
        
        @if($product->images->count() > 1)
            <div class="thumbs">
                @foreach($product->images as $image)
                    <img src="{{ asset($image->path) }}" alt="{{ $product->name }}">
                @endforeach
            </div>
        @endif
    </div>

    <!-- Информация о товаре -->
    <div class="product-info">
        <h1>{{ $product->name }}</h1>
        <p class="price big">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
        
        <div class="availability">
            @if($product->stock > 0)
                <span class="in-stock">В наличии: {{ $product->stock }} шт.</span>
            @else
                <span class="out-of-stock">Нет в наличии</span>
            @endif
        </div>

        <div class="specs">
            <h3>Характеристики</h3>
            <table>
                <tr>
                    <th>Артикул:</th>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <th>Материал:</th>
                    <td>{{ $product->material }}</td>
                </tr>
                @if($product->hallmark)
                <tr>
                    <th>Проба:</th>
                    <td>{{ $product->hallmark }}</td>
                </tr>
                @endif
                @if($product->weight)
                <tr>
                    <th>Вес:</th>
                    <td>{{ $product->weight }} г</td>
                </tr>
                @endif
                @if($product->stone)
                <tr>
                    <th>Камни:</th>
                    <td>{{ $product->stone }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="description">
            <h3>Описание</h3>
            <p>{{ $product->description }}</p>
        </div>

        @if($product->stock > 0)
            <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                @csrf
                <div class="quantity-selector">
                    <label for="quantity">Количество:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                </div>
                <button type="submit" class="btn">Добавить в корзину</button>
            </form>
        @endif

        @auth
            <form action="{{ route('wishlist.add', $product) }}" method="POST">
                @csrf
                <button type="submit" class="btn-muted">♥ В избранное</button>
            </form>
        @endauth
    </div>
</div>

<!-- Отзывы -->
<section class="reviews-section">
    <h2>Отзывы о товаре</h2>
    
    <!-- Средний рейтинг -->
    <div class="average-rating">
        <div class="rating-stars">
            @for($i = 1; $i <= 5; $i++)
                <span class="star {{ $i <= round($product->average_rating) ? 'filled' : '' }}">★</span>
            @endfor
        </div>
        <span class="rating-value">{{ number_format($product->average_rating, 1) }} / 5</span>
        <span class="reviews-count">({{ $product->reviews_count }})</span>
    </div>
    
    <!-- Форма добавления отзыва (только для авторизованных) -->
    @auth
        @php
            $userReview = $product->reviews()->where('user_id', Auth::id())->first();
        @endphp
        
        @if(!$userReview)
            <div class="add-review">
                <h3>Оставить отзыв</h3>
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="review-form">
                    @csrf
                    
                    <div class="form-group">
                        <label>Ваша оценка *</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                <label for="star{{ $i }}">★</label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="advantages">Достоинства</label>
                        <input type="text" name="advantages" id="advantages" value="{{ old('advantages') }}" placeholder="Что понравилось?">
                        @error('advantages')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="disadvantages">Недостатки</label>
                        <input type="text" name="disadvantages" id="disadvantages" value="{{ old('disadvantages') }}" placeholder="Что не понравилось?">
                        @error('disadvantages')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Комментарий</label>
                        <textarea name="comment" id="comment" rows="4" placeholder="Поделитесь впечатлениями о товаре">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn">Отправить отзыв</button>
                </form>
            </div>
        @endif
    @endauth
    
    @guest
        <p class="login-to-review"><a href="{{ route('login') }}">Войдите</a>, чтобы оставить отзыв</p>
    @endguest
    
    <!-- Список отзывов -->
    <div class="reviews-list">
        @forelse($product->approvedReviews()->with('user')->latest()->get() as $review)
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">{{ $review->user->name }}</span>
                    <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                </div>
                
                @if($review->advantages)
                    <div class="review-advantages">
                        <strong>Достоинства:</strong> {{ $review->advantages }}
                    </div>
                @endif
                
                @if($review->disadvantages)
                    <div class="review-disadvantages">
                        <strong>Недостатки:</strong> {{ $review->disadvantages }}
                    </div>
                @endif
                
                @if($review->comment)
                    <div class="review-comment">
                        {{ $review->comment }}
                    </div>
                @endif
                
                @can('update', $review)
                    <div class="review-actions">
                        <button class="btn-muted edit-review" data-review-id="{{ $review->id }}">Редактировать</button>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-muted" onclick="return confirm('Удалить отзыв?')">Удалить</button>
                        </form>
                    </div>
                @endcan
            </div>
        @empty
            <p class="no-reviews">Пока нет отзывов. Будьте первым!</p>
        @endforelse
    </div>
</section>

<!-- Похожие товары -->
@if($related->isNotEmpty())
<section class="related-products">
    <h2>Похожие товары</h2>
    <div class="grid-3">
        @foreach($related as $relatedProduct)
            <article class="product-card">
                <a href="{{ route('catalog.show', $relatedProduct) }}">
                    <img src="{{ asset($relatedProduct->mainImage()) }}" alt="{{ $relatedProduct->name }}">
                </a>
                <h3><a href="{{ route('catalog.show', $relatedProduct) }}">{{ $relatedProduct->name }}</a></h3>
                <p class="price">{{ number_format($relatedProduct->price, 0, '.', ' ') }} руб.</p>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection