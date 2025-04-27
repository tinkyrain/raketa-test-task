create table if not exists products
(
    uuid  char(36) not null primary key unique comment 'UUID товара',
    category char(36) not null comment 'UUID категории',
    is_active tinyint default 1  not null comment 'Флаг активности',
    name varchar(255) not null comment 'Наименование товара',
    description text null comment 'Описание товара',
    thumbnail  varchar(255) null comment 'Ссылка на картинку',
    price decimal(10, 2) not null comment 'Цена',
    CONSTRAINT fk_products_category FOREIGN KEY (category) REFERENCES categories(uuid)
)
    comment 'Товары';

create index is_active_idx on products (is_active);
create index category_idx on products (category);
create index name_idx on products (name);

create table if not exists categories
(
    uuid  char(36) not null primary key unique comment 'UUID категории',
    name text not null comment 'Наименование категории'
)
    comment 'Категории';

create index name_idx on categories (name);
    
