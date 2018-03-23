<?php
namespace Barbarossa42\NextrasOrmCommand;

use Nette\PhpGenerator\PhpNamespace;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateOrmCommand
 * @package App\Console
 * create:orm article Articles\Article articles
 */
class CreateOrmCommand extends Command
{
    const ORM_DIR = __DIR__ . '/../orm/';

    const NS_DEFAULT = 'App\Orm';

    const NS_ENTITY = 'Nextras\Orm\Entity\Entity';

    const NS_REPOSITORY = 'Nextras\Orm\Repository\Repository';

    const NS_MAPPER = 'Nextras\Orm\Mapper\Mapper';

    private $name = '';

    private $namespace = '';

    private $table = '';

    protected function configure()
    {
        $this->setName('create:orm');
        $this->addArgument('name', InputArgument::REQUIRED, 'name of class');
        $this->addArgument('namespace', InputArgument::OPTIONAL, 'namespace');
        $this->addArgument('table', InputArgument::OPTIONAL, 'name of table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $dirName = $name;
        $this->name = Strings::firstUpper($name);
        $this->namespace = $input->getArgument('namespace') ? $input->getArgument('namespace') : Strings::firstUpper($dirName);
        $this->table = $input->getArgument('table') ? $input->getArgument('table') : $name;

        $dir = self::ORM_DIR . $dirName;
        if(is_dir($dir)) $dir .= '-2';
        mkdir($dir);

        file_put_contents("$dir/$this->name.php", $this->getEntity());
        file_put_contents("$dir/$this->name" . "Repository.php", $this->getRepository());
        file_put_contents("$dir/$this->name" . "Mapper.php", $this->getMapper());

        $output->writeLn('Orm directory created successfully');
        return 0;
    }

    public function getEntity()
    {
        $namespace = new PhpNamespace(self::NS_DEFAULT . "\\$this->namespace");
        $namespace->addUse(self::NS_ENTITY);

        $class = $namespace->addClass($this->name);
        $class->setExtends(self::NS_ENTITY)
            ->addComment("$this->name Entity class")
            ->addComment('@property int $id {primary}');

        return (string) "<?php " . PHP_EOL . $namespace;
    }

    public function getRepository()
    {
        $namespace = new PhpNamespace(self::NS_DEFAULT . "\\$this->namespace");
        $namespace->addUse(self::NS_REPOSITORY);

        $class = $namespace->addClass($this->name . 'Repository');
        $class->setExtends(self::NS_REPOSITORY);

        $class->addMethod('getEntityClassNames')
            ->setReturnType('array')
            ->setStatic()
            ->setBody("return [$this->name::class];");

        return (string) "<?php " . PHP_EOL . $namespace;
    }

    public function getMapper()
    {
        $namespace = new PhpNamespace(self::NS_DEFAULT . "\\$this->namespace");
        $namespace->addUse(self::NS_MAPPER);

        $class = $namespace->addClass($this->name . 'Mapper');
        $class->setExtends(self::NS_MAPPER);

        $class->addProperty('tableName', $this->table)
            ->setVisibility('protected');

        return (string) "<?php " . PHP_EOL . $namespace;
    }
}