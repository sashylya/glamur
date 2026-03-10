@extends('layouts.app')

@section('title', 'Контакты - Гламур')

@section('content')
<div class="page-content">
    <h1>Контакты</h1>
    
    <div class="contacts-grid">
        <div class="contact-info">
            <h2>Наши контакты</h2>
            
            <div class="contact-item">
                <h3>Адрес:</h3>
                <p>г. Москва, ул. Примерная, д. 123</p>
            </div>
            
            <div class="contact-item">
                <h3>Телефон:</h3>
                <p><a href="tel:+79991234567">+7 (999) 123-45-67</a></p>
            </div>
            
            <div class="contact-item">
                <h3>Email:</h3>
                <p><a href="mailto:info@glamur.ru">info@glamur.ru</a></p>
            </div>
            
            <div class="contact-item">
                <h3>Режим работы:</h3>
                <p>Пн-Пт: 10:00 - 19:00</p>
                <p>Сб-Вс: выходной</p>
            </div>
        </div>
        
        <div class="contact-form">
            <h2>Напишите нам</h2>
            
            <form method="POST" action="#" class="feedback-form">
                @csrf
                
                <div class="form-group">
                    <label for="name">Ваше имя *</label>
                    <input type="text" name="name" id="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Сообщение *</label>
                    <textarea name="message" id="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn">Отправить</button>
            </form>
        </div>
    </div>
</div>
@endsection