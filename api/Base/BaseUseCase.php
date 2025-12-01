<?php

namespace Base;

abstract class BaseUseCase
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public static function getInstance(?object $repository = null): static
    {
        if ($repository) {
            return new static(new $repository);
        } else {
            $repositoryClass = static::resolveRepositoryClass();
            return new static(new $repositoryClass());
        }
    }

    protected static function resolveRepositoryClass(): string
    {
        $calledClass = static::class;

        // Exemplo: Src\Domain\Name\UseCases\NameMemberFindAllUseCase
        $parts = explode('\\', $calledClass);

        // Extrai domínio (3ª parte) => "Scale"
        $domain = $parts[2] ?? 'Unknown';

        // Extrai nome da classe final (sem namespace)
        $className = end($parts);

        // Remove sufixo "UseCase"
        $baseName = str_replace('UseCase', '', $className);

        // Remove possíveis prefixos de ação (Create, Update, FindAll, etc) para obter o nome da entidade alvo
        $entityName = preg_replace('/(Create|Update|Delete|Find(All|ById|ByCriteria|Paged)?)$/', '', $baseName);
        if (empty($entityName)) {
            $entityName = $domain; // fallback
        }

        // Monta o caminho completo do repositório
        $repositoryClass = "Src\\Domain\\{$domain}\\{$entityName}Repository";

        if (!class_exists($repositoryClass)) {
            throw new \RuntimeException("Repository class not found: {$repositoryClass} at {$calledClass}", 500);
        }

        return $repositoryClass;
    }
}
