<?php
class conditionalCacheFilter extends sfFilter
{
	public function execute($filterChain)
	{
		$context = $this->getContext();
		//if (!$context->getUser()->isAuthenticated())
		//{
		$viewCacheManager = $context->getViewCacheManager();
		foreach ($this->getParameter('pages') as $page){
			if ($viewCacheManager) $viewCacheManager->addCache($page['module'], $page['action'], array('lifeTime' => 3600));
		}
		//}

		// Execute next filter
		$filterChain->execute();
	}
}