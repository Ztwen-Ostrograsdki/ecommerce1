<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'Les Articles';

    public static ?string $label = "Les Articles";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Details articles')->schema([
                        TextInput::make('name')
                                ->label('Nom')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        TextInput::make('slug')
                                ->label('Identifiant')
                                ->disabled()
                                ->required()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true)
                                ->maxLength(255),

                        MarkdownEditor::make('description')
                                       ->label("Decrivez l'article")
                                       ->columnSpanFull()
                                       ->fileAttachmentsDirectory('products')

                    ])->columns(2),

                    Section::make('Images Associées')->schema([
                        FileUpload::make('images')
                                   ->label('Les images associées')
                                   ->multiple()
                                   ->directory('products')
                                   ->reorderable()
                                   ->maxFiles(5)

                    ])
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Détails Prix')->schema([
                        TextInput::make('price')
                                  ->label('Le prix')
                                  ->required()
                                  ->prefix('FCFA')

                    ]),

                    Section::make('Associations')->schema([
                        Select::make('category_id')
                                  ->label('La catégorie')
                                  ->required()
                                  ->searchable()
                                  ->preload()
                                  ->relationship('category', 'name'),
                        
                        Select::make('brand_id')
                                  ->label('La Marque/game')
                                  ->required()
                                  ->searchable()
                                  ->preload()
                                  ->relationship('brand', 'name')
                                  
                    ]),

                    Section::make('Status')->schema([
                        Toggle::make('in_stock')
                               ->label('Est disponible ?')
                               ->required()
                               ->default(true),

                        Toggle::make('is_active')
                               ->label('Est actif ?')
                               ->required()
                               ->default(true),
                        
                        Toggle::make('is_featured')
                               ->label('Article Spécial ?')
                               ->required(),

                        Toggle::make('on_sale')
                               ->label('En vente ?')
                               ->required()
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label("Article")->searchable()->sortable(),

                TextColumn::make('category.name')->label("Catégorie")->searchable(),

                TextColumn::make('brand.name')->label("La marque")->sortable()->searchable(),

                TextColumn::make('price')->label("Le prix")->money("CFA", 0, 'fr')->sortable(),

                IconColumn::make('is_featured')->boolean()->label("Article spécial ?"),

                IconColumn::make('on_sale')->label("En vente ?")->boolean(),

                IconColumn::make('in_stock')->label("Disponible ?")->boolean(),

                IconColumn::make('is_active')->label("Est actif ?")->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Date de Création")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label("Date de MAJ")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name'),
                SelectFilter::make('brand')->relationship('brand', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() >= 10 ? static::getModel()::count() : '0' . static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
