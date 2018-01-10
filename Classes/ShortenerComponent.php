<?php
declare(strict_types=1);

namespace Ttree\ResourceShortener;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\Component\ComponentChain;
use Neos\Flow\Http\Component\ComponentContext;
use Neos\Flow\Http\Component\ComponentInterface;
use Neos\Flow\Http\Headers;
use Neos\Neos\Domain\Repository\DomainRepository;

class ShortenerComponent implements ComponentInterface
{
    /**
     * @var DomainRepository
     * @Flow\Inject
     */
    protected $domainRepository;

    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function handle(ComponentContext $componentContext)
    {
        if (!isset($this->options['sites']) || !\is_array($this->options['sites']) || $this->options['sites'] === []) {
            return;
        }
        $httpRequest = $componentContext->getHttpRequest();
        $path = $httpRequest->getRelativePath();

        $domain = $this->domainRepository->findOneByActiveRequest();
        if ($domain === null) {
            return;
        }
        $siteNodeName = $domain->getSite()->getNodeName();
        if (!isset($this->options['sites'][$siteNodeName]) || !\is_array($this->options['sites'][$siteNodeName])) {
            return;
        }

        $configuredPath = array_keys($this->options['sites'][$siteNodeName]);

        if (!\in_array($path, $configuredPath)) {
            return;
        }

        $resource = $this->options['sites'][$siteNodeName][$path]['resource'];

        if (!@\is_file($resource)) {
            return;
        }

        $content = \file_get_contents($resource);
        $httpResponse = $componentContext->getHttpResponse();

        if (isset($this->options['sites'][$siteNodeName][$path]['headers']) && \is_array($this->options['sites'][$siteNodeName][$path]['headers'])) {
            $httpResponse->setHeaders(new Headers($this->options['sites'][$siteNodeName][$path]['headers']));
        }

        $httpResponse->setHeader('Content-Length', \mb_strlen($content));
        $httpResponse->setContent($content);

        $componentContext->setParameter(ComponentChain::class, 'cancel', true);
    }
}
