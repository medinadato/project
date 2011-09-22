<?php

/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Pager
 *
 * @author      Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @package     Doctrine
 * @subpackage  Pager
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version     $Revision$
 * @link        www.doctrine-project.org
 * @since       0.9
 */
namespace Core\Grid;

class Pager
{

    /**
     * @var integer $_numResults        Number of results found
     */
    protected $numResults;
    /**
     * @var integer $_maxPerPage        Maximum number of itens per page
     */
    protected $maxPerPage = 25;
    /**
     * @var integer $page               Current page
     */
    protected $page;
    /**
     * @var integer $_lastPage          Last page (total of pages)
     */
    protected $lastPage;
    /**
     * pager offset
     * @var int
     */
    protected $offset;

    /**
     * __construct
     *
     * @param mixed $query     Accepts either a Doctrine_Query object or a string 
     *                        (which does the Doctrine_Query class creation).
     * @param int $page     Current page
     * @param int $maxPerPage     Maximum itens per page
     * @return void
     */
    public function __construct($numResults, $page, $maxPerPage = 0)
    {
	$this->setNumResults($numResults)
	     ->setPage($page)
	     ->setMaxPerPage($maxPerPage)
	     ->adjustOffset();
	return $this;
    }

    /**
     * Adjusts last page of Pager, offset and limit
     * @return void
     */
    protected function adjustOffset()
    {
	// Define new total of pages
	$this->setLastPage(
	    max(1, ceil($this->getNumResults() / $this->getMaxPerPage()))
	);
	$offset = ($this->getPage() - 1) * $this->getMaxPerPage();
	$this->offset = $offset;
    }
    
    /**
     * returns the offset 
     * @return int
     */
    public function getOffset()
    {
	return $this->offset;
    }

    /**
     * sets the source to paginate
     * @param \Core\Grid\Source\ISource $source
     * @return Pager 
     */
    public function setSource(\Core\Grid\Source\ISource $source)
    {
	$this->source = $source;
	return $this;
    }

    /**
     * getNumResults
     *
     * Returns the number of results found
     *
     * @return int        the number of results found
     */
    public function getNumResults()
    {
	return $this->numResults;
    }

    /**
     * setNumResults
     *
     * Defines the number of total results on initial query
     *
     * @param $nb       Number of results found on initial query fetch
     * @return void
     */
    protected function setNumResults($nb)
    {
	$this->numResults = (int) $nb;
	return $this;
    }

    /**
     * getFirstPage
     *
     * Returns the first page
     *
     * @return int        first page
     */
    public function getFirstPage()
    {
	return 1;
    }

    /**
     * getLastPage
     *
     * Returns the last page (total of pages)
     *
     * @return int        last page (total of pages)
     */
    public function getLastPage()
    {
	return $this->lastPage;
    }

    /**
     * Returns total of pages
     * @return int
     */
    public function getTotalPages()
    {
	return $this->getLastPage();
    }

    /**
     * setLastPage
     *
     * Defines the last page (total of pages)
     *
     * @param $page       last page (total of pages)
     * @return void
     */
    protected function setLastPage($page)
    {
	$this->lastPage = $page;

	if ($this->getPage() > $page) {
	    $this->setPage($page);
	}
    }

    /**
     * getLastPage
     *
     * Returns the current page
     *
     * @return int        current page
     */
    public function getPage()
    {
	return $this->page;
    }

    /**
     * getNextPage
     *
     * Returns the next page
     *
     * @return int        next page
     */
    public function getNextPage()
    {
	return min($this->getPage() + 1, $this->getLastPage());
    }

    /**
     * getPreviousPage
     *
     * Returns the previous page
     *
     * @return int        previous page
     */
    public function getPreviousPage()
    {
	//if ($this->getExecuted()) {
	return max($this->getPage() - 1, $this->getFirstPage());
	//}
    }

    /**
     * getFirstIndice
     *
     * Return the first indice number for the current page
     *
     * @return int First indice number
     */
    public function getFirstIndice()
    {
	return ($this->getPage() - 1) * $this->getMaxPerPage() + 1;
    }

    /**
     * getLastIndice
     *
     * Return the last indice number for the current page
     *
     * @return int Last indice number
     */
    public function getLastIndice()
    {
	return min($this->getNumResults(), ($this->getPage() * $this->getMaxPerPage()));
    }

    /**
     *
     * Return true if it's necessary to paginate or false if not
     *
     * @return bool
     */
    public function haveToPaginate()
    {
	return $this->getNumResults() > $this->getMaxPerPage();
    }

    /**
     * setPage
     *
     * Defines the current page
     *
     * @param $page       current page
     * @return void
     */
    public function setPage($page)
    {
	$page = intval($page);
	$this->page = ($page <= 0) ? 1 : $page;
	return $this;
    }

    /**
     * getLastPage
     *
     * Returns the maximum number of itens per page
     *
     * @return int        maximum number of itens per page
     */
    public function getMaxPerPage()
    {
	return $this->maxPerPage;
    }

    /**
     * setMaxPerPage
     *
     * Defines the maximum number of itens per page and automatically adjust offset and limits
     *
     * @param $max       maximum number of itens per page
     * @return Pager
     */
    public function setMaxPerPage($max)
    {
	if ($max > 0) {
	    $this->maxPerPage = $max;
	} else if ($max == 0) {
	    $this->maxPerPage = 25;
	} else {
	    $this->maxPerPage = abs($max);
	}
	
	return $this;
    }

    /**
     * getResultsInPage
     *
     * Returns the number of itens in current page
     *
     * @return int    Number of itens in current page
     */
    public function getResultsInPage()
    {
	$page = $this->getPage();

	if ($page != $this->getLastPage()) {
	    return $this->getMaxPerPage();
	}

	$offset = ($this->getPage() - 1) * $this->getMaxPerPage();

	return abs($this->getNumResults() - $offset);
    }

}